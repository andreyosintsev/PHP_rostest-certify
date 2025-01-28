=== kk Star Ratings ===
Contributors: bhittani
Donate link: https://github.com/kamalkhan/kk-star-ratings
Tags: star ratings, votings, rate posts, ajax ratings, infinite stars, unlimited stars, google rich snippets, structured data, SEO, SERP
Requires at least: 4.5
Requires PHP: 5.5.9
Tested up to: 5.2.2
Stable tag: 3.1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html



kk Star Ratings allows blog visitors to involve and interact more effectively with your website by rating posts.

This plugin has been renewed from the ground up as of v3 covered by unit tests.



== Description ==



kk Star Ratings is a widely used star rating plugin for wordpress. Here are some highlighted features:

- User defined amount of star ratings (5 as default) in your **posts**, **pages** and publicly accesible **custom post types**.

- Structured data supporting **google rich snippets** showing the star ratings in search results which has the potential to drive more traffic to your website.

- Widespread coverage of custom hooks.

- Full control via options page. You can,

  - Enable or disable globally.
  
  - Disable star ratings in posts that belong to certain categories.

  - Choose where to show the star ratings. It can be on the **homepage**, in **archives**, in **posts**, in **pages** and/or in **custom post types**.

  - Control the structured data schema and type.

  - Restrict votings per unique ip.

  - Allow voting in archives.

  - Allow guests to vote.

  - Customize position within the post content.

  - Adjust the number of stars.

  - Adjust the amount of stars.

  - Adjust the colors of the stars.
  
- And much more...

== Installation ==






1. Extract the plugin zip file.
1. Upload/move the folder `kk-star-ratings` to the `wp-content/plugins` directory.
1. Activate the plugin via the wordpress plugins dashboard page.
1. Adjust the plugin options under the kk Star Ratings menu in wp-admin.

== Frequently Asked Questions ==



= What should I do if structured data do not show in search result pages. =


Please have patience as we have no control over how google or any search engine indexes your pages. It might take some days or even weeks for the robots to crawl the ratings.

= I have been using a verion of this plugin prior to v3. Is it safe for me to update? =


All previous ratings and options will be preserved. However, since v3 has been renewed from scratch, we do not support downgrading to v2 after upgrading to v3.

= I found a bug or want to contribute. =


The source of this plugin is located at [Github](https://github.com/kamalkhan/kk-star-ratings). Feel free to post an issue or submit a pull request.

== Screenshots ==

1. Appearance

== Changelog ==

= 3.1.1 =

**Fixed **
- GitHub PR #84: Voting is now disabled on the current page when unique IP is enforced.

**Changed**
- GitHub PR #83: Trim extra spacing in the legend.

= 3.1.0 =

**Added**
- Bottom margin added when bottom position in effect.
- Ability to reset ratings for individual posts/pages.
- Enable/disable star ratings for individual posts/pages.
- Take manual control of the auto embedded markup to avoid duplication when using in a template.

**Fixed**
- Markup is now hidden under AMP.
- Assets are now enqueued when manually/forcefully loading the markup via template function.

**Changed**
- Default colors have been updated.
- Default size is now 22px instead of 24px.
- Structured data now uses ratingCount instead of reviewCount.

**Removed**
- Nothing

**Deprecated**
- Nothing

**Security**
- Nothing

= 3.0.0 =

**Added**
- Optionally allow guests to vote.
- Ratings can also be included in publicly accessible custom posts.

**Fixed**
- Google rich snippets.
- AJAX call on every load causing high CPU usage.

**Changed**
- Stars are now based on svg.
- Appearance has been simplified.
- Html based structured data has been replaced by json based structured data.
- kk_star_ratings_get function no longer includes the post_title key.

**Removed**
- Labels have been removed.
- Top posts widget has been removed in favor of a future addon.
- Admin table colum has been removed in favor of a future addon.

**Deprecated**
- Nothing

**Security**
- Nothing

= 2.x =
[Archived](.github/CHANGELOG-v2.md)

= 1.x =
[Archived](.github/CHANGELOG-v1.md)

== Upgrade Notice ==

= 3.0.0 =
All previous ratings and options will be preserved. However, since v3 has been renewed from scratch, we do not support downgrading to v2 after moving from v2 to v3.


