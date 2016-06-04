.PHONY: clean %.html

FILES = \
	dist/index.html \
	dist/layout_table.html \
	dist/layout_plain.html \
	dist/layout_bootstrap.html \
	dist/usage.html \
	dist/options.html \
	dist/autolink.js \
	dist/style.css

all: $(FILES)

clean:
	@rm -rf dist

dist:
	@mkdir -p $@

%.html: dist force-rebuild
	@echo Writing $@
	@php $(basename $(notdir $@)).php | sed -e 's/\.php/.html/g' -e 's#index\.html#/php-forms#' > $@

%.css %.js: dist force-rebuild
	@echo Copying $@
	@cp $(notdir $@) dist

force-rebuild:
