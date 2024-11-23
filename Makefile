ZIP := xfeket01.zip
# ZIP := xkotvi01.zip

pack:
	zip -r $(ZIP) \
		app/Enums/* \
		app/Http/Controllers/* \
		app/Models/* \
		app/Services/* \
		database/migrations/* \
		routes/web.php \
		docs/doc.html \
		docs/DB_Scheme.png \
		docs/use_case_diagram.svg

clean:
	rm -f ./*.zip
