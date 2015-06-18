UPDATE `road_info`.`Route` SET `short_name`='RUV_AKU_EGI' WHERE `id`='1';
UPDATE `road_info`.`Route` SET `short_name`='RUV_AKU_OLFJ' WHERE `id`='2';
UPDATE `road_info`.`Route` SET `short_name`='RUV_AKU_VOPN' WHERE `id`='3';
UPDATE `road_info`.`Route` SET `short_name`='RUV_BLO_SKAG' WHERE `id`='4';
UPDATE `road_info`.`Route` SET `short_name`='RUV_BORG_HUS' WHERE `id`='5';
UPDATE `road_info`.`Route` SET `short_name`='RUV_BORG_SFN' WHERE `id`='6';
UPDATE `road_info`.`Route` SET `short_name`='RUV_BRJ_ARNF' WHERE `id`='7';
UPDATE `road_info`.`Route` SET `short_name`='RUV_EGI_FIRD' WHERE `id`='8';
UPDATE `road_info`.`Route` SET `short_name`='RUV_EGI_HROA' WHERE `id`='9';
UPDATE `road_info`.`Route` SET `short_name`='RUV_HALE_LANDMLAU' WHERE `id`='10';
UPDATE `road_info`.`Route` SET `short_name`='RUV_HALE_SPR_KJOL' WHERE `id`='11';
UPDATE `road_info`.`Route` SET `short_name`='RUV_HBORGARSV' WHERE `id`='12';
UPDATE `road_info`.`Route` SET `short_name`='RUV_HFN_EGI' WHERE `id`='13';
UPDATE `road_info`.`Route` SET `short_name`='RUV_HVALFJRD' WHERE `id`='14';
UPDATE `road_info`.`Route` SET `short_name`='RUV_KRO_TING' WHERE `id`='15';
UPDATE `road_info`.`Route` SET `short_name`='RUV_REY_AKU' WHERE `id`='16';
UPDATE `road_info`.`Route` SET `short_name`='RUV_REY_GRIN' WHERE `id`='17';
UPDATE `road_info`.`Route` SET `short_name`='RUV_REY_HFN' WHERE `id`='18';
UPDATE `road_info`.`Route` SET `short_name`='RUV_REY_ISA_THROSK' WHERE `id`='19';
UPDATE `road_info`.`Route` SET `short_name`='RUV_SEL_TING' WHERE `id`='20';
UPDATE `road_info`.`Route` SET `short_name`='RUV_TING_SUG' WHERE `id`='21';
UPDATE `road_info`.`Route` SET `short_name`='RUV_UPPHERAD' WHERE `id`='22';
UPDATE `road_info`.`Route` SET `short_name`='RUV_VARM_SIG' WHERE `id`='23';


DELETE FROM road_info.RouteHasSegment
WHERE route_id = 1 AND segment_id = 910110001;

DELETE FROM road_info.RouteHasSegment
WHERE route_id = 1 AND segment_id = 910080001;

DELETE FROM road_info.RouteHasSegment
WHERE route_id = 1 AND segment_id = 910240001;

DELETE FROM road_info.RouteHasSegment
WHERE route_id = 1 AND segment_id = 910230001;

DELETE FROM road_info.RouteHasSegment
WHERE route_id = 2 AND segment_id = 909080001;

INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (3, 909040001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (3, 910240001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (3, 910420001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (3, 910220001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (3, 910430001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (3, 910410001);

UPDATE `road_info`.`Route` SET `long_name`='Akureyri - Húsavík/Kópask/Raufarh/Þórsh/Vopnafjörður' WHERE `id`='3';

INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (4, 908190001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (4, 908180001);

DELETE FROM road_info.RouteHasSegment
WHERE route_id = 5 AND segment_id = 904100001;
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (5, 904090001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (5, 904120001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (5, 804060001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (5, 904150001);

UPDATE `road_info`.`Route` SET `long_name`='Borgarnes-Húsafell/Skorradalur' WHERE `id`='5';

INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (6, 904370001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (6, 904320001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (6, 905000001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (6, 905010001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (6, 905020001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (6, 905030001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (6, 904070001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (6, 904050001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (6, 904040001);

INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (7, 905250001);
DELETE FROM `road_info`.`RouteHasSegment` WHERE `route_id`='7' and`segment_id`='905280001';

INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (9, 911040001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (9, 911030001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (9, 910330001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (9, 910320001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (9, 910440001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (9, 910220001);

INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (10, 901090001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (10, 901360001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (10, 902110001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (10, 901350001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (10, 901340001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (10, 901330001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (10, 901080001);

INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (11, 901360001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (11, 902110001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (11, 901350001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (11, 901340001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (11, 901330001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (11, 913470001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (11, 914230001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (11, 914520001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (11, 908060001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (11, 909110001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (11, 910060001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (11, 902180001);

INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (13, 912050001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (13, 911400001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (13, 911070001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (13, 911700001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (13, 911060001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (13, 912110001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (13, 911500001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (13, 911050001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (13, 911040001);
UPDATE `road_info`.`Route` SET `long_name`='Höfn - Egilsstaðir, allar leiðir' WHERE `id`='13';

DELETE FROM road_info.RouteHasSegment WHERE route_id = 14 AND segment_id = 904150001;
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (14, 904190001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (14, 904510001);

INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (15, 906040001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (15, 906030001);

INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (21, 906700001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (21, 906120001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (21, 906130001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (21, 906710001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (21, 906110001);
UPDATE `road_info`.`Route` SET `long_name`='Þingeyri-Ísafjörður,Súgandafj.,Bolungarvík' WHERE `id`='21';

INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (16, 904690001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (16, 904000001);

UPDATE `road_info`.`Route` SET `short_name`='RUV_REY_SNES', `long_name`='Reykjavík - Suðurnes' WHERE `id`='17';
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (17, 903200001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (17, 903150001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (17, 903300001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (17, 903190001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (17, 903180001);

INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (18, 902330001);

INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (19, 904690001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (19, 904000001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (19, 905030001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (19, 905070001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (19, 906130001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (19, 906710001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (19, 906110001);
UPDATE `road_info`.`Route` SET `long_name`='Reykjavík-Ísafjörður-Bolungavík um Þröskulda' WHERE `id`='19';

DELETE FROM road_info.Route
WHERE id = 22;

INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (23, 909300001);

UPDATE road_info.RouteHasSegment SET route_id = 20 WHERE route_id = 878;
UPDATE `road_info`.`Route` SET `short_name`='RUV_GULLNI', `long_name`='Gullni hringurinn' WHERE `id`='20';
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (20, 902220001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (20, 903070001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (20, 902030001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (20, 902020001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (20, 902010001);
INSERT INTO road_info.RouteHasSegment(route_id, segment_id) VALUES (20, 903010001);