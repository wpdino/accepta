<?php
/**
 * Font Awesome Icons List
 * 
 * Popular Font Awesome icons for the icon picker
 *
 * @package Accepta
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get popular Font Awesome icons
 */
function accepta_get_fontawesome_icons() {
	return array(
		// Social Media
		'Social Media' => array(
			'fab fa-facebook-f' => 'Facebook',
			'fab fa-twitter' => 'Twitter',
			'fab fa-instagram' => 'Instagram',
			'fab fa-linkedin-in' => 'LinkedIn',
			'fab fa-youtube' => 'YouTube',
			'fab fa-pinterest-p' => 'Pinterest',
			'fab fa-tiktok' => 'TikTok',
			'fab fa-snapchat-ghost' => 'Snapchat',
			'fab fa-whatsapp' => 'WhatsApp',
			'fab fa-telegram-plane' => 'Telegram',
			'fab fa-discord' => 'Discord',
			'fab fa-reddit-alien' => 'Reddit',
			'fab fa-tumblr' => 'Tumblr',
			'fab fa-flickr' => 'Flickr',
			'fab fa-vimeo-v' => 'Vimeo',
			'fab fa-twitch' => 'Twitch',
			'fab fa-github' => 'GitHub',
			'fab fa-gitlab' => 'GitLab',
			'fab fa-bitbucket' => 'Bitbucket',
			'fab fa-dribbble' => 'Dribbble',
			'fab fa-behance' => 'Behance',
			'fab fa-medium-m' => 'Medium',
			'fab fa-dev' => 'Dev.to',
			'fab fa-stack-overflow' => 'Stack Overflow',
		),
		
		// Communication
		'Communication' => array(
			'fas fa-envelope' => 'Email',
			'fas fa-phone' => 'Phone',
			'fas fa-mobile-alt' => 'Mobile',
			'fas fa-fax' => 'Fax',
			'fas fa-comments' => 'Comments',
			'fas fa-comment' => 'Comment',
			'fas fa-comment-dots' => 'Comment Dots',
			'fas fa-sms' => 'SMS',
			'fas fa-inbox' => 'Inbox',
			'fas fa-paper-plane' => 'Send',
		),
		
		// Business & Office
		'Business' => array(
			'fas fa-briefcase' => 'Briefcase',
			'fas fa-building' => 'Building',
			'fas fa-chart-bar' => 'Chart Bar',
			'fas fa-chart-line' => 'Chart Line',
			'fas fa-chart-pie' => 'Chart Pie',
			'fas fa-clipboard' => 'Clipboard',
			'fas fa-file' => 'File',
			'fas fa-folder' => 'Folder',
			'fas fa-calendar' => 'Calendar',
			'fas fa-clock' => 'Clock',
			'fas fa-user-tie' => 'Business Person',
			'fas fa-handshake' => 'Handshake',
			'fas fa-trophy' => 'Trophy',
			'fas fa-award' => 'Award',
			'fas fa-medal' => 'Medal',
		),
		
		// Technology
		'Technology' => array(
			'fas fa-laptop' => 'Laptop',
			'fas fa-desktop' => 'Desktop',
			'fas fa-mobile' => 'Mobile',
			'fas fa-tablet' => 'Tablet',
			'fas fa-keyboard' => 'Keyboard',
			'fas fa-mouse' => 'Mouse',
			'fas fa-wifi' => 'WiFi',
			'fas fa-bluetooth' => 'Bluetooth',
			'fas fa-usb' => 'USB',
			'fas fa-hard-drive' => 'Hard Drive',
			'fas fa-database' => 'Database',
			'fas fa-server' => 'Server',
			'fas fa-cloud' => 'Cloud',
			'fas fa-code' => 'Code',
			'fas fa-terminal' => 'Terminal',
		),
		
		// Navigation & UI
		'Navigation' => array(
			'fas fa-home' => 'Home',
			'fas fa-user' => 'User',
			'fas fa-users' => 'Users',
			'fas fa-cog' => 'Settings',
			'fas fa-search' => 'Search',
			'fas fa-heart' => 'Heart',
			'fas fa-star' => 'Star',
			'fas fa-bookmark' => 'Bookmark',
			'fas fa-share' => 'Share',
			'fas fa-download' => 'Download',
			'fas fa-upload' => 'Upload',
			'fas fa-print' => 'Print',
			'fas fa-edit' => 'Edit',
			'fas fa-trash' => 'Delete',
			'fas fa-plus' => 'Add',
			'fas fa-minus' => 'Remove',
			'fas fa-check' => 'Check',
			'fas fa-times' => 'Close',
			'fas fa-arrow-up' => 'Arrow Up',
			'fas fa-arrow-down' => 'Arrow Down',
			'fas fa-arrow-left' => 'Arrow Left',
			'fas fa-arrow-right' => 'Arrow Right',
			'fas fa-chevron-up' => 'Chevron Up',
			'fas fa-chevron-down' => 'Chevron Down',
			'fas fa-chevron-left' => 'Chevron Left',
			'fas fa-chevron-right' => 'Chevron Right',
		),
		
		// Shopping & E-commerce
		'Shopping' => array(
			'fas fa-shopping-cart' => 'Shopping Cart',
			'fas fa-shopping-bag' => 'Shopping Bag',
			'fas fa-credit-card' => 'Credit Card',
			'fas fa-money-bill' => 'Money',
			'fas fa-dollar-sign' => 'Dollar',
			'fas fa-euro-sign' => 'Euro',
			'fas fa-pound-sign' => 'Pound',
			'fas fa-yen-sign' => 'Yen',
			'fas fa-gift' => 'Gift',
			'fas fa-tag' => 'Tag',
			'fas fa-tags' => 'Tags',
			'fas fa-barcode' => 'Barcode',
			'fas fa-receipt' => 'Receipt',
		),
		
		// Location & Travel
		'Location' => array(
			'fas fa-map-marker-alt' => 'Location',
			'fas fa-map' => 'Map',
			'fas fa-globe' => 'Globe',
			'fas fa-plane' => 'Plane',
			'fas fa-car' => 'Car',
			'fas fa-bus' => 'Bus',
			'fas fa-train' => 'Train',
			'fas fa-ship' => 'Ship',
			'fas fa-bicycle' => 'Bicycle',
			'fas fa-walking' => 'Walking',
			'fas fa-suitcase' => 'Suitcase',
			'fas fa-hotel' => 'Hotel',
			'fas fa-compass' => 'Compass',
		),
		
		// Food & Dining
		'Food' => array(
			'fas fa-utensils' => 'Utensils',
			'fas fa-coffee' => 'Coffee',
			'fas fa-wine-glass' => 'Wine',
			'fas fa-beer' => 'Beer',
			'fas fa-pizza-slice' => 'Pizza',
			'fas fa-hamburger' => 'Hamburger',
			'fas fa-ice-cream' => 'Ice Cream',
			'fas fa-birthday-cake' => 'Cake',
			'fas fa-apple-alt' => 'Apple',
			'fas fa-carrot' => 'Carrot',
		),
		
		// Health & Medical
		'Health' => array(
			'fas fa-heart' => 'Heart',
			'fas fa-heartbeat' => 'Heartbeat',
			'fas fa-plus-square' => 'Medical Cross',
			'fas fa-hospital' => 'Hospital',
			'fas fa-ambulance' => 'Ambulance',
			'fas fa-user-md' => 'Doctor',
			'fas fa-stethoscope' => 'Stethoscope',
			'fas fa-pills' => 'Pills',
			'fas fa-syringe' => 'Syringe',
			'fas fa-thermometer' => 'Thermometer',
		),
		
		// Education
		'Education' => array(
			'fas fa-graduation-cap' => 'Graduation',
			'fas fa-book' => 'Book',
			'fas fa-book-open' => 'Open Book',
			'fas fa-pencil-alt' => 'Pencil',
			'fas fa-pen' => 'Pen',
			'fas fa-highlighter' => 'Highlighter',
			'fas fa-eraser' => 'Eraser',
			'fas fa-calculator' => 'Calculator',
			'fas fa-microscope' => 'Microscope',
			'fas fa-flask' => 'Flask',
		),
	);
}
