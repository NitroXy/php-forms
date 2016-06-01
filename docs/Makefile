.PHONY: clean %.html

FILES = \
	dist/index.html \
	dist/layout_table.html \
	dist/layout_plain.html \
	dist/layout_bootstrap.html \
	dist/options.html \
	dist/style.css

all: $(FILES)

clean:
	@rm -rf dist

dist:
	@mkdir -p $@

%.html: dist force-rebuild
	@echo Writing $@
	@php $(basename $(notdir $@)).php | sed -e 's/\.php/.html/g' -e 's#index\.html#/php-forms#' > $@

%.css: dist force-rebuild
	@cp $(notdir $@) dist

force-rebuild:
