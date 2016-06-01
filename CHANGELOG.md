# 1.2.3

## Features

  - Bootstrap: improved group rendering using grid, will autobalance
    by default but column classes can be set manually on fields.
  - Hints: cleaner row- and field-level hints.

## Bugfixes

  - Table: fixed group rendering for forms with only groups.

# 1.2.2

## Breaking changes

  - Layout `render_fieldset` changed prototype, now includes
    `$children_cb` which must be called to render all the content
    inside the fieldset.

## Bugfixes

  - Fieldset rendering now works correctly when mixing fields inside
    and outside fieldsets.

# 1.2.1

## Bugfixes

  - `begin` is now always called, fixes issues with forms containing only groups.

## Features

  - Checkbox using labels in table layout.
  - Exposed `start`, `end` and `render` as protected.

# 1.2.0 (2016-05-30)

## Breaking changes

  - Layout classes must now implement `render_static`.
  - Layout for checkboxes changed, label is now called `text`.

## Bugfixes

  - Unbuffered output regression fix since 1.1.0
  - Support static fields for all layouts.

## Features

  - Checkboxes support label=false the same way other fields does.

# 1.1.0 (2016-05-29)

## Breaking changes

  - Layout classes must now implement `preamble` and `postamble` functions.
  - Layout classes must now implement `render_hidden` function.

## Bugfixes

  - Fixed layout being instance instead of string.

## Features

  - Supports additional HTTP methods (PATCH, DELETE, etc).
  - Support customizing `<form>` wrapper using `preamble` and `postamble`.
