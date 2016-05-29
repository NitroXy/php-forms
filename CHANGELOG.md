## 1.1.0

Breaking changes:
  - Layout classes must now implement `preamble` and `postamble` functions.

Bugfixes:
  - Fixed layout being instance instead of string.

Features:
  - Supports additional HTTP methods (PATCH, DELETE, etc).
  - Support customizing `<form>` wrapper using `preamble` and `postamble`.
