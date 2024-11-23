ZIP := xfeket01.zip
# ZIP := xkotvi01.zip

pack:
	zip -r $(ZIP) \
		app/Enums/* \
		app/Http/Controllers/* \
		app/Models/* \
		app/Services/* \
		resources/views/* \
		database/migrations/* \
		database/seeders/* \
		routes/web.php \
		docs/doc.html \
		docs/DB_Scheme.png \
		docs/use_case_diagram.svg \
        README.md

clean:
	rm -f ./*.zip
