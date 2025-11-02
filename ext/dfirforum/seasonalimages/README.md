# phpBB Seasonal Images Extension

A flexible phpBB extension that displays seasonal images on your forum based on configurable date ranges throughout the year.

## Features

- **Date-Based Configuration**: Set up multiple date ranges throughout the year, each with its own image
- **ACP Management Interface**: Easy-to-use admin panel for managing seasonal images
- **Flexible Positioning**: Choose from 7 different position options (top-left, top-right, center, etc.)
- **Priority System**: Control which image displays when date ranges overlap
- **Year-Boundary Support**: Handle date ranges that cross year boundaries (e.g., Dec 25 - Jan 5)
- **Enable/Disable Toggle**: Quickly enable or disable seasonal images without deleting them
- **Responsive Design**: Automatically hides on mobile devices for optimal display
- **Performance Optimized**: Uses caching to minimize database queries

## Installation

1. Download the latest release
2. Extract the files to your phpBB installation directory, maintaining the folder structure
3. The extension should be located at: `phpBB/ext/dfirforum/seasonalimages/`
4. Navigate to `ACP → Customise → Manage extensions`
5. Find "Seasonal Images" in the list and click `Enable`
6. Navigate to `ACP → Extensions → Seasonal Images` to configure your seasonal images

## Configuration

### Adding a Seasonal Image

1. Go to `ACP → Extensions → Seasonal Images`
2. Click "Add Seasonal Image"
3. Fill in the form:
   - **Description**: A name for this seasonal image (e.g., "Christmas Hat")
   - **Start Date**: Month and day when the image should start displaying
   - **End Date**: Month and day when the image should stop displaying
   - **Image Filename**: Name of the image file (must be uploaded to `ext/dfirforum/seasonalimages/styles/all/theme/images/`)
   - **Position**: Where the image should appear
   - **Priority**: Higher numbers display first when ranges overlap
   - **Enabled**: Check to enable this seasonal image
4. Click "Submit"

### Date Ranges

Date ranges can be configured to:
- Span within a single year (e.g., March 1 - March 31)
- Cross year boundaries (e.g., December 20 - January 5)

The extension uses the forum's timezone settings for date comparison.

### Position Options

- **Top Left**: Display in the top-left corner
- **Top Right**: Display in the top-right corner (default for avatar overlays)
- **Top Center**: Display centered at the top
- **Bottom Left**: Display in the bottom-left corner
- **Bottom Right**: Display in the bottom-right corner
- **Bottom Center**: Display centered at the bottom
- **Center**: Display in the center

### Priority System

When multiple date ranges overlap, the image with the highest priority value will be displayed. This allows you to control which image takes precedence during overlapping periods.

### Adding Images

1. Upload your image files to: `ext/dfirforum/seasonalimages/styles/all/theme/images/`
2. Supported formats: PNG (recommended for transparency), JPG, GIF
3. Recommended sizes:
   - Small overlays (avatars): 47x47 pixels
   - Large overlays (logo): 110x127 pixels
4. Enter just the filename in the ACP (e.g., `christmas_hat.png`)

## Example Use Cases

Create seasonal atmosphere throughout the year:

- **New Year** (Jan 1-5): Party hat
- **Valentine's Day** (Feb 14): Heart
- **St. Patrick's Day** (Mar 17): Shamrock
- **Halloween** (Oct 31): Pumpkin
- **Christmas** (Dec 20-26): Christmas hat

## Technical Details

### Database Schema

The extension creates a table `phpbb_seasonal_images` with the following structure:

- `id` - Primary key
- `start_month` - Start month (1-12)
- `start_day` - Start day (1-31)
- `end_month` - End month (1-12)
- `end_day` - End day (1-31)
- `image_path` - Image filename
- `enabled` - Enabled status (0/1)
- `position` - Display position
- `priority` - Priority for overlapping ranges
- `description` - Description of the seasonal image

### Caching

Active seasonal images are cached for 1 hour to minimize database queries. The cache is automatically cleared when you add, edit, or delete seasonal images in the ACP.

### Compatibility

- **phpBB Version**: 3.3.0 or higher
- **PHP Version**: 7.1.3 or higher

## Uninstallation

1. Navigate to `ACP → Customise → Manage extensions`
2. Click "Disable" for Seasonal Images
3. Click "Delete Data" to remove the database table
4. Delete the `/ext/dfirforum/seasonalimages` folder

## Support

For issues, questions, or feature requests, please visit:
https://github.com/dfirforum/seasonalimages/issues

## License

[GNU General Public License v2](http://opensource.org/licenses/GPL-2.0)

## Credits

Based on the original Christmas Hat extension by Origin.
Transformed into a flexible seasonal image system by DFIR Forum.

## Changelog

### Version 1.0.0 (2025-11-02)
- Initial release
- Date-based seasonal image system
- ACP management interface
- Support for multiple date ranges
- Flexible positioning options
- Priority system for overlapping ranges
- Year-boundary date range support
- Performance optimizations with caching
