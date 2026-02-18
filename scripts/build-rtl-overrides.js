#!/usr/bin/env node
/**
 * Build accepta-rtl.css with only the rules that differ from LTR.
 * Uses rtlcss to generate full RTL, then diffs against LTR and outputs only overrides.
 * Reduces RTL file size significantly for wp.org.
 */
'use strict';

const fs = require('fs');
const path = require('path');
const postcss = require('postcss');
const rtlcss = require('rtlcss');

const ROOT = path.resolve(__dirname, '..');
const LTR_FILE = path.join(ROOT, 'assets/css/accepta.css');
const RTL_OUTPUT = path.join(ROOT, 'assets/css/accepta-rtl.css');

// Load rtlcss config from package.json if present
let rtlcssOptions = {};
try {
  const pkg = JSON.parse(fs.readFileSync(path.join(ROOT, 'package.json'), 'utf8'));
  if (pkg.rtlcssConfig) {
    rtlcssOptions = pkg.rtlcssConfig.options || {};
  }
} catch (e) {
  // use defaults
}

function declsSignature(rule) {
  const decls = [];
  rule.walkDecls((d) => decls.push(`${d.prop}:${d.value}`));
  return decls.sort().join(';');
}

function getContextKey(node) {
  const parts = [];
  let n = node.parent;
  while (n && n.type !== 'root') {
    if (n.type === 'atrule') {
      parts.unshift(`@${n.name} ${n.params}`);
    }
    n = n.parent;
  }
  return parts.join(' ');
}

function ruleKey(rule) {
  const context = getContextKey(rule);
  const sel = rule.selector;
  return context ? `${context} ${sel}` : sel;
}

function buildSignatureMap(root) {
  const map = new Map();
  root.walkRules((rule) => {
    if (rule.parent.type === 'atrule' && rule.parent.name === 'keyframes') return;
    const key = ruleKey(rule);
    const sig = declsSignature(rule);
    map.set(key, sig);
  });
  return map;
}

function collectDifferingRules(rtlRoot, ltrMap, rtlMap, out) {
  rtlRoot.walkRules((rule) => {
    if (rule.parent.type === 'atrule' && rule.parent.name === 'keyframes') return;
    const key = ruleKey(rule);
    const rtlSig = rtlMap.get(key);
    const ltrSig = ltrMap.get(key);
    if (rtlSig != null && rtlSig !== ltrSig) {
      out.push({ rule, parent: rule.parent });
    }
  });
}

function main() {
  const ltrCss = fs.readFileSync(LTR_FILE, 'utf8');
  const ltrRoot = postcss.parse(ltrCss);
  const ltrMap = buildSignatureMap(ltrRoot);

  const rtlResult = postcss([rtlcss(rtlcssOptions)]).process(ltrCss, { from: LTR_FILE });
  const rtlRoot = rtlResult.root;
  const rtlMap = buildSignatureMap(rtlRoot);

  const differing = [];
  collectDifferingRules(rtlRoot, ltrMap, rtlMap, differing);

  const output = postcss.root();
  output.append(postcss.rule({ selector: 'html' }).append(postcss.decl({ prop: 'direction', value: 'rtl' })));

  const byMedia = new Map();
  const noMedia = [];
  for (const { rule, parent } of differing) {
    const clone = rule.clone();
    if (parent.type === 'atrule' && parent.name === 'media') {
      const params = parent.params;
      if (!byMedia.has(params)) byMedia.set(params, []);
      byMedia.get(params).push(clone);
    } else {
      noMedia.push(clone);
    }
  }
  for (const r of noMedia) output.append(r);
  for (const [params, rules] of byMedia) {
    const atRule = postcss.atRule({ name: 'media', params });
    for (const r of rules) atRule.append(r);
    output.append(atRule);
  }

  const resultCss = output.toResult().css;
  fs.writeFileSync(RTL_OUTPUT, resultCss, 'utf8');
  console.log('RTL overrides written to assets/css/accepta-rtl.css');
}

main();
