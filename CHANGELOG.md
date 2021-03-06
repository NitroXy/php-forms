# 1.5.0

## Features

  - New `FormBuilder` class allows easier currying of fields.
  - Form ID false disables ID.
  - Passing null to both `fromArray` and `fromObject` is now allowed.

## Breaking changes

  - **ALL** Methods names are now camelCase (PSR-1).
  - PHP 5.6 or later is now required.
  - Select API change: prototype more similar to other fields, replaced
    `FormSelect` with `FormOptions`.
  - Error messages will no longer be pulled via hardcoded `errors`
    field, to get errors the `FormData::getErrorFor($field)`method
    must be overridden.
  - Buttons name attribute is now always unprefixed.

## Bugfixes

  - Buttons now sets name attribute properly again.
  - Select now escapes label again.

# 1.4.1

## Bugfixes

  - Fix call to protected method generate_data when nesting FormSelect
    in containers.

# 1.4.0

## Breaking changes

  - Form attributes is now pulled directly from `$options`, `attr`
    option is still present but deprecated.

# 1.3.0

## Features

  - Form: support addons: prefix and suffix.

# 1.2.4

## Breaking changes

  - Layout: empty groups are no longer rendered

## Bugfixes

  - Table: group hint rendering fixed

## Features

  - Form: allow arbitrary attributes to be passed to form.
  - Styling: checkboxes uses `form-checkbox` class.
  - Styling: all layouts now adds `form-group` class.
  - Styling: required fields get a `required` class.

# 1.2.3

## Features

  - Bootstrap: improved group rendering using grid, will autobalance
    by default but column classes can be set manually on fields.
  - Hints: cleaner row- and field-level hints.
  - Table: using labels with `for` on all variants.

## Bugfixes

  - File upload: `current` option using correct key.
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
