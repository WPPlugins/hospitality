=== Plugin Name ===
Contributors: wkempferjr, jnobles
Donate link: http://guestaba.com/donate
Tags: hotel, resort, hospitality, reservation
Requires at least: 4.0
Tested up to: 4.6.1
Stable tag: 1.0.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The Hospitality Plugin and Shortcodes simplify creating and editing rooms, amenities and prices.

== Description ==

[youtube https://www.youtube.com/watch?v=ye7AvH0i0eA&feature=youtu.be]

The Hospitality plugin features a built-in reservation system with online and offline payment options plus a set of room management features. It simplifies how hotels, motels, resorts, and other businesses manage the display of rooms and meeting spaces on their WordPress powered website. It simplifies creating room pages and room listing pages with templates. It simplifies managing prices and amenities as well. You can create, save and edit multiple lists of amenities and prices for easy re-use. Update changes once in the dashboard and it updates across your website.

The plugin installs demo data upon activiation. To see the reservation system in action, create a new page
with the slug 'room-search' and insert the [room_reservation] shortcode. Incidentally, the slug can
be changed in Hospitality->Settings->Reservation Entry Point Slug.

##Latest Updates
-Reservation system built in. See details below.
-Support for rooms located at different physical locations. See details below.
-Added Font Awesome icons. Plugin ships with a copy of Font Awesome for use in Amenity Sets. Currently icons only show on Room Listing page.
-New Room Listing layout with search and sorting functions.
-Switched to Bootstrap and Angular.js frameworks to speed up future development
-6 new overview videos hosted on our [YouTube Channel.](https://www.youtube.com/channel/UC-c8krYZFZvguU5eIzg5McA "Guestaba Tutorial Videos")


> ##Guestaba on Social Media
>> [Find us on Facebook](https://www.facebook.com/GuestabaHospitalityWordpress)

>> [Follow us on Twitter](https://twitter.com/Guestaba)

>> [Sign up for our newsletter to get the full scoop on latest updates.](http://eepurl.com/bwPtWT "Guestaba Product Newsletter, hosted by Mailchimp")


== Reservation System ==
A new reservation system is included in the latest plugin version. It includes two payment methods, Paypal Express for online payments and an offline method. The offline methods holds the
room until it is manually updated in the WordPress Dashboard. Support for payments via Stripe are planned for the near future.

###Additional Reservation System Features
-Options to include taxes and additional fees at checkout

-Support for multiple locations, so a single website can handle reservations for rooms at different physical locations. The locations include address and maps so users can easily see where the room is located. Payment options must still go through a single account.

-Automatic reservation confirmation emails. Guests will receive a confirmation email containing reservation details and custom text.

-Dashboard for sorting and editing reservations.  

= Custom Post Types =

The plugin the following custom post types:
- Room
- Amenity Sets
- Pricing Models
- Locations
- Reservations
- Room Locations

=  Amenity Sets =

Amenity Sets provide a way to create reusable lists of standard room amenities. I

= Pricing Models =

Pricing models provide a way to create reusable price group lists for a particular room which may vary based on the seasons, holidays, and local events.

= Rooms and Room Listings =

The room post type adds images and text to the preconfigured Amenity Sets and Pricing Models to create an individual room page.
Multiple individual room pages are collected and displayed on the Rooms Listing page featuring a thumbnail, pricing information,
an excerpt and a link to the individual room.

The room post template that comes with the plugin will display the room title, an image slider, the full room description, amenities, and prices.
Three widget areas are provided on the room post page the permit display of reservation and booking forms, guest policies, maps, directions
and other information that you want your guests to know.

== Widget Areas ==

The room detail page provides three sidebars (or widget areas). The Room First, Second, and Third widget areas, are
positioned respectively at the top, middle, and bottom area of the page. This is useful for entering and displaying
content that is common to all rooms. Maps, directions, guest policies, and reservation forms are examples of what you
could integrate into the plugin via the widget areas.

== Additional Features ==
A built-in slider makes it easy to upload and display images for each room. Or, if you already have a favorite slider,
it can be easily integrated with the hospitality plugin.

= Shortcodes =

To maximize flexibility in layout, the plugin includes several shortcodes. Room listings can be displayed on any page of your site simply by inserting the room listing shortcode, which is **[room_listing]**. If the single
room page layout does not meet your needs, each component of the a room post--images, description, thumbnail, and rate information--can be
arbitrarily positioned and styled via their respective shortcode.

== Installation ==

From the Wordpress Dashboard Plugins->Add New page:

1. Search for "Hospitality". Find Hospality from Guestaba in the listing and clic its "Install Now".
1. Click the "Activate Now" once the plugin is downloaded and installed.

or

1. Download the plugin to a local folder on your computer from the WordPress plugin repository.
1. On the Plugin->Add New page, click the "Upload Plugin" button, navigate to wherever you have downloaded plugin zip file,
and then select it to start the upload.
1.  Click the "Activate Now" link to activate.

The plugin can also by installed by unzipping the hospitality.zip file to your wp-content/plugins folder.

Once the plugin is installed, you can find its settings page by clicking the "action" link found in the Hospitality listing in the
dashboard Plugins page, or got to Settings->Hospitality. See our [support site](http://support.guestaba.com/support/home) to find out how
configure the plugin and begin entering information about your rooms and meeting spaces.


== Frequently Asked Questions ==

See our the most up-to-date version of our [help documentation](http://support.guestaba.com/support/home)


== Screenshots ==

1. A room and rates listing.
2. Adding a new room: name and description fields.
3. Adding a new room: amenties and pricing model fields.
4. Adding a new room: image slider and related fields.
5. Adding amenity set listing.
6. Adding a new amenity set.

== Changelog ==

= 1.0.0 =
* The initial version.

= 1.0.1 =
* Corrected markdown errors in readme.txt.
* Corrected location of screenshots.

= 1.0.3 =
* Ability to specify room prices by the day of the week.
* Date coverage checking in pricing model.

= 1.0.4 =
* Added location map
* Support amenity icons in room listing.

= 1.0.5 =
* Added reservation system with PayPal Express and offline payment gateways

= 1.0.6 =
* Minor bug fixes and corrections to README.txt

== Upgrade Notice ==

= 1.0.0 =
Initial version.

= 1.0.1 =
Administrative fixes. No code changes.

= 1.0.2 =
Added support for user-specified currency symbol.
Misc administrative code for handling plugin upgrades.

= 1.0.3 =
Ability to specify room prices by the day of the week.
Date coverage checking in pricing model.

= 1.0.4 =
Added location map
Support amenity icons in room listing.

= 1.0.5 =
* Added reservation system with PayPal Express and offline payment gateways

= 1.0.6 =
* Minor bug fixes and corrections to README.txt
