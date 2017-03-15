# New region français établis hors de France
INSERT INTO `p_l_region` (`p_l_country_id`, `title`, `uuid`, `created_at`, `updated_at`, `slug`)
VALUES ('1', 'Français établis hors de France', NULL, now(), now(), 'francais-de-l-etranger');

# app/console politizr:uuids:populate PLRegion

# New departements circonscriptions français établis hors de France
# Première circonscription    États-Unis et Canada
# Deuxième circonscription    Amérique du Sud, Amérique Centrale, Mexique et Antilles
# Troisième circonscription   Europe du Nord
# Quatrième circonscription   Benelux
# Cinquième circonscription   Péninsule ibérique et Monaco
# Sixième circonscription     Suisse et Liechtenstein
# Septième circonscription    Europe de l'Est (hors Russie), Europe centrale et Balkans
# Huitième circonscription    Chypre, Grèce, Israël, Italie, Malte, et Turquie
# Neuvième circonscription    Maghreb, Afrique de l'Ouest
# Dixième circonscription     Partie du Moyen-Orient et de l'Afrique
# Onzième circonscription     Asie (incl. Russie) et Océanie

INSERT INTO `p_l_department` (`p_l_region_id`, `code`, `title`, `uuid`, `created_at`, `updated_at`, `slug`)
VALUES
('15', NULL, 'États-Unis et Canada', NULL, now(), now(), 'etats-unis-et-canada'),
('15', NULL, 'Amérique du Sud, Amérique Centrale, Mexique et Antilles', NULL, now(), now(), 'amerique-du-sud-amerique-centrale-mexique-et-antilles'),
('15', NULL, 'Europe du Nord', NULL, now(), now(), 'europe-du-nord'),
('15', NULL, 'Benelux', NULL, now(), now(), 'benelux'),
('15', NULL, 'Péninsule ibérique et Monaco', NULL, now(), now(), 'peninsule-iberique-et-monaco'),
('15', NULL, 'Suisse et Liechtenstein', NULL, now(), now(), 'suisse-et-liechtenstein'),
('15', NULL, 'Europe de l\'Est (hors Russie), Europe centrale et Balkans', NULL, now(), now(), 'europe-de-l-est-hors-russie-europe-centrale-et-balkans'),
('15', NULL, 'Chypre, Grèce, Israël, Italie, Malte, et Turquie', NULL, now(), now(), 'israel-italie-malte-et-turquie'),
('15', NULL, 'Maghreb, Afrique de l\'Ouest', NULL, now(), now(), 'maghreb-afrique-de-l-ouest'),
('15', NULL, 'Partie du Moyen-Orient et de l\'Afrique', NULL, now(), now(), 'partie-du-moyen-orient-et-de-l-afrique'),
('15', NULL, 'Asie (incl. Russie) et Océanie', NULL, now(), now(), 'asie-incl-russie-et-oceanie');

# app/console politizr:uuids:populate PLDepartment

INSERT INTO `p_l_city` (`p_l_department_id`, `slug`, `name`, `name_simple`, `name_real`, `created_at`, `updated_at`)
VALUES
('108', 'ville-etats-unis-et-canada', 'VILLE DANS ÉTATS-UNIS ET CANADA', 'ville dans états-unis et canada', 'Ville dans États-Unis et Canada', now(), now()),
('109', 'ville-amerique-du-sud-amerique-centrale-mexique-et-antilles', 'VILLE DANS AMÉRIQUE DU SUD, AMÉRIQUE CENTRALE, MEXIQUE ET ANTILLES', 'ville dans amérique du sud, amérique centrale, mexique et antilles', 'Ville dans Amérique du Sud, Amérique Centrale, Mexique et Antilles', now(), now()),
('110', 'ville-europe-du-nord', 'VILLE DANS EUROPE DU NORD', 'ville dans europe du nord', 'Ville Dans Europe du Nord', now(), now()),
('111', 'ville-benelux', 'VILLE DANS BENELUX', 'ville dans benelux', 'Ville dans Benelux', now(), now()),
('112', 'ville-peninsule-iberique-et-monaco', 'VILLE DANS PÉNINSULE IBÉRIQUE ET MONACO', 'ville dans péninsule ibérique et monaco', 'Ville dans Péninsule ibérique et Monaco', now(), now()),
('113', 'ville-suisse-et-liechtenstein', 'VILLE DANS SUISSE ET LIECHTENSTEIN', 'ville dans suisse et liechtenstein', 'Ville dans Suisse et Liechtenstein', now(), now()),
('114', 'ville-europe-de-l-est-hors-russie-europe-centrale-et-balkans', 'VILLE DANS EUROPE DE L\'EST (HORS RUSSIE), EUROPE CENTRALE ET BALKANS', 'ville dans europe de l\'est (hors russie), europe centrale et balkans', 'Ville dans Europe de l\'Est (hors Russie), Europe centrale et Balkans', now(), now()),
('115', 'ville-israel-italie-malte-et-turquie', 'VILLE DANS CHYPRE, GRÈCE, ISRAËL, ITALIE, MALTE, ET TURQUIE', 'ville dans chypre, grèce, israël, italie, malte, et turquie', 'Ville dans Chypre, Grèce, Israël, Italie, Malte, et Turquie', now(), now()),
('116', 'ville-maghreb-afrique-de-l-ouest', 'VILLE DANS MAGHREB, AFRIQUE DE L\'OUEST', 'ville dans maghreb, afrique de l\'ouest', 'Ville dans Maghreb, Afrique de l\'Ouest', now(), now()),
('117', 'ville-partie-du-moyen-orient-et-de-l-afrique', 'VILLE DANS PARTIE DU MOYEN-ORIENT ET DE L\'AFRIQUE', 'ville dans partie du moyen-orient et de l\'afrique', 'Ville dans Partie du Moyen-Orient et de l\'Afrique', now(), now()),
('118', 'ville-asie-incl-russie-et-oceanie', 'VILLE DANS ASIE (INCL. RUSSIE) ET OCÉANIE', 'ville dans asie (incl. russie) et océanie', 'Ville dans Asie (incl. Russie) et Océanie', now(), now());

# app/console politizr:uuids:populate PLCity
