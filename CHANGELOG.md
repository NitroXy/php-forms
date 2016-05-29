# 1.2.0 (2016-05-30)

## Breaking changes

  - Layout classes must now implement `render_static`.

## Bugfixes

  - Unbuffered output regression fix since 1.1.0
  - Support static fields for all layouts.

# 1.1.0 (2016-05-29)

## Breaking changes

  - Layout classes must now implement `preamble` and `postamble` functions.
  - Layout classes must now implement `render_hidden` function.

## Bugfixes

  - Fixed layout being instance instead of string.

## Features

  - Supports additional HTTP methods (PATCH, DELETE, etc).
  - Support customizing `<form>` wrapper using `preamble` and `postamble`.
