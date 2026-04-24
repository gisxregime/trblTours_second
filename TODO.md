# Dashboard UI Updates

- [x] Update app/Http/Controllers/DashboardController.php
  - [x] Ensure tour listings are restricted to verified guides only
  - [x] Preserve existing tourist feed sorting/pagination logic

- [x] Redesign resources/views/dashboards/tourist.blade.php
  - [x] Add sticky navbar with scroll-state gold semi-transparent background
  - [x] Use logo image (/images/tribaltours_icon.png) + Asimovian brand name "TrblTours"
  - [x] Add profile dropdown items:
    - [x] Messages
    - [x] Profile Dashboard (edit profile)
    - [x] My Posts
    - [x] My Bookings and Rate
    - [x] Notifications
    - [x] Settings
    - [x] Logout
  - [x] Add sticky filter bar under navbar (Location + Sort By)
  - [x] Add floating "Create Request Post" button (tourist-only)
  - [x] Restyle tour listing cards (verified badge, title, location, duration, price, Book Now button)
  - [x] Ensure request post cards stay functional and visually aligned
  - [x] Remove comment/like UI presence

- [ ] Guide dashboard temporary unavailable screen
  - [ ] Update `DashboardController@guide` to return new unavailable view
  - [ ] Create styled Blade view for unavailable state

- [ ] Run validation checks
  - [ ] Syntax/lint sanity check for updated files
  - [ ] Confirm TODO completion status
