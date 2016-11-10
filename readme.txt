EDD Downloads Lists

In development

Based on EDD Favorites, adds the possibility to add multiple user wish lists. Currently adds:
List (For a custom collection of lists)
Wish List (as EDD Favorites, a user list with all user wished downloads)
Favorite (as EDD Favorites, a user list with all user favorited downloads)
Like (as EDD Favorites, a user list with all user liked downloads)

To see how to define custom list see file edd-downloads-list.php get_lists function
Adding a list will, automatically add all settings in EDD Settings -> Extensions -> EDD Downloads Lists

TODO:
Support to add custom collection lists (for example the list "List") to grouping list
Improve templating

Done:
Filter lists queries to hide single lists ("likes" do not appear on "List"): I need this PR on EDD Wish List to get it done (https://github.com/easydigitaldownloads/edd-wish-lists/pull/51)