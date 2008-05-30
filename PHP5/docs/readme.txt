Datenbank:  
    Zeiterfassung
    
Tabellen:   
    Mitarbeiter
    ZeitBuchung
    ZeitKonto
    ZBTyp

Mitarbeiter:
	[MId]
	[Namen]
	[Vornamen]
	[LoginNamen]
	[LoginPasswort]
	
ZeitBuchung:
	[Bid]
	[TypId]
	[Datum]
	[Mid]
	[KstId]
	[KoaId]
	
ZeitKonto:
	[Mid]
	[Periode]
	[Jahr]
	[MinSoll]
	[MinHaben]
	[MinSaldo]
	
ZBTyp:
	[TypId] -> ZeitBuchung.TypId
	[Bezeichnung]
	[Symbol]
