<?php
/**
 *
 * Seasonal Images. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2025, DFIR Forum
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = [];
}

$lang = array_merge($lang, [
	// ACP Module
	'ACP_SEASONAL_IMAGES_TITLE'				=> 'Seasonal Images',
	'ACP_SEASONAL_IMAGES_SETTINGS'			=> 'Seasonal Images Settings',
	'ACP_SEASONAL_IMAGES_EXPLAIN'			=> 'Manage seasonal images that display on your forum during specific date ranges throughout the year.',

	// ACP List
	'ACP_SEASONAL_IMAGES_DESCRIPTION'		=> 'Description',
	'ACP_SEASONAL_IMAGES_START_DATE'		=> 'Start Date',
	'ACP_SEASONAL_IMAGES_END_DATE'			=> 'End Date',
	'ACP_SEASONAL_IMAGES_IMAGE'				=> 'Image',
	'ACP_SEASONAL_IMAGES_POSITION'			=> 'Position',
	'ACP_SEASONAL_IMAGES_PRIORITY'			=> 'Priority',
	'ACP_SEASONAL_IMAGES_STATUS'			=> 'Status',
	'ACP_SEASONAL_IMAGES_ACTIONS'			=> 'Actions',
	'ACP_SEASONAL_IMAGES_NONE'				=> 'No seasonal images configured yet.',

	// ACP Actions
	'ACP_SEASONAL_IMAGES_ADD'				=> 'Add Seasonal Image',
	'ACP_SEASONAL_IMAGES_EDIT'				=> 'Edit Seasonal Image',
	'ACP_SEASONAL_IMAGES_DELETE'			=> 'Delete Seasonal Image',
	'ACP_SEASONAL_IMAGES_DELETE_CONFIRM'	=> 'Are you sure you want to delete this seasonal image?',

	// ACP Edit Form
	'ACP_SEASONAL_IMAGES_EDIT_EXPLAIN'		=> 'Add or edit a seasonal image configuration.',
	'ACP_SEASONAL_IMAGES_START_DATE_EXPLAIN'=> 'Select the month and day when this image should start displaying.',
	'ACP_SEASONAL_IMAGES_END_DATE_EXPLAIN'	=> 'Select the month and day when this image should stop displaying. Can cross year boundaries (e.g., Dec 25 to Jan 5).',
	'ACP_SEASONAL_IMAGES_IMAGE_PATH'		=> 'Image Filename',
	'ACP_SEASONAL_IMAGES_IMAGE_PATH_EXPLAIN'=> 'Enter the filename of the image (must be uploaded to ext/dfirforum/seasonalimages/styles/all/theme/images/).',
	'ACP_SEASONAL_IMAGES_POSITION_EXPLAIN'	=> 'Choose where the image should appear.',
	'ACP_SEASONAL_IMAGES_PRIORITY_EXPLAIN'	=> 'Higher numbers have priority when multiple date ranges overlap. Higher priority displays first.',
	'ACP_SEASONAL_IMAGES_ENABLED'			=> 'Enabled',

	// Position Options
	'ACP_SEASONAL_IMAGES_POSITION_TOP_LEFT'		=> 'Top Left',
	'ACP_SEASONAL_IMAGES_POSITION_TOP_RIGHT'	=> 'Top Right',
	'ACP_SEASONAL_IMAGES_POSITION_TOP_CENTER'	=> 'Top Center',
	'ACP_SEASONAL_IMAGES_POSITION_BOTTOM_LEFT'	=> 'Bottom Left',
	'ACP_SEASONAL_IMAGES_POSITION_BOTTOM_RIGHT'	=> 'Bottom Right',
	'ACP_SEASONAL_IMAGES_POSITION_BOTTOM_CENTER'=> 'Bottom Center',
	'ACP_SEASONAL_IMAGES_POSITION_CENTER'		=> 'Center',

	// Messages
	'ACP_SEASONAL_IMAGES_ADDED'				=> 'Seasonal image added successfully.',
	'ACP_SEASONAL_IMAGES_UPDATED'			=> 'Seasonal image updated successfully.',
	'ACP_SEASONAL_IMAGES_DELETED'			=> 'Seasonal image deleted successfully.',
	'ACP_SEASONAL_IMAGES_NOT_FOUND'			=> 'Seasonal image not found.',

	// Validation Errors
	'ACP_SEASONAL_IMAGES_INVALID_START_DATE'=> 'Invalid start date.',
	'ACP_SEASONAL_IMAGES_INVALID_END_DATE'	=> 'Invalid end date.',
	'ACP_SEASONAL_IMAGES_INVALID_IMAGE_PATH'=> 'Image filename is required.',
]);
