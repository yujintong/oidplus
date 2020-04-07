INSERT IGNORE INTO `asn1id` VALUES (2256,'oid:1.3.6.1.4.1.37553.8.32488192274','example',0,'\0');
INSERT IGNORE INTO `asn1id` VALUES (2257,'oid:2.999.1','example1',0,'\0');
INSERT IGNORE INTO `asn1id` VALUES (2258,'oid:2.999.2','example2',0,'\0');
INSERT IGNORE INTO `asn1id` VALUES (2259,'oid:2.999.1.123','delegated',0,'\0');

INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('doi:10.1000','doi:','DOI Foundation (Example)','<p><strong>DOI Foundation</strong></p>\n<p>This DOI is only included as an example.<br />Please delete this object once your database goes online.</p>','','\0','2018-09-30 22:47:29','2019-03-10 22:42:31');
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('doi:10.1000/182','doi:10.1000','DOI Handbook','<p><b>DOI Handbook</b></p>\n<p>This DOI is only included as an example.<br />Please delete this object once your database goes online.</p>','','\0','2018-09-30 22:47:20',NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('doi:10.1371','doi:','CNRI (Example)','<p><strong>Corporation for National Research Initiatives (CNRI)</strong></p>\n<p>This DOI is only included as an example.<br />Please delete this object once your database goes online.</p>','','\0','2018-09-30 22:47:40','2019-03-10 22:42:37');
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('doi:10.1371/journal.pbio.0020449','doi:10.1371','Cro-Magnons Conquered Europe, but Left Neanderthals Alone','<p><b>Cro-Magnons Conquered Europe, but Left Neanderthals Alone</b></p>\n<p>This DOI is only included as an example.<br />Please delete this object once your database goes online.</p>','','\0','2018-09-30 22:46:27',NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('doi:10.1371/journal.pbio.0020449.g001','doi:10.1371/journal.pbio.0020449','Reconstruction of Neanderthal woman','<p><strong>Reconstruction of Neanderthal woman</strong></p>\n<p>This DOI is only included as an example.<br />Please delete this object once your database goes online.</p>','','\0','2018-09-30 22:46:58',NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('gs1:0012000','gs1:','Pepsi-Cola North America Inc. (Example)','<p>This GS1 prefix/vendor is only included as an example.<br />Please delete this object once your database goes online.</p>','','\0',NULL,'2019-03-19 08:42:30');
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('gs1:001200000000','gs1:0012000','GLN','','','\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('gs1:001200000017','gs1:0012000','Pepsi, 24ct, 12 oz Cans','','','\0',NULL,'2019-03-10 22:38:25');
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('gs1:001200000129','gs1:0012000','Pepsi Cola, 20-Ounce Containers (Pack of 24)','','','\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('gs1:001200000130','gs1:0012000','Pepsi 20-fl oz Cola','','','\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:0139d44e-6afe-49f2-8690-3dafcae6ffb8','guid:filedialog','CommonPrograms','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:054fae61-4dd8-4787-80b6-090220c4b700','guid:filedialog','GameTasks','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:0762d272-c50a-4bb0-a382-697dcd729b80','guid:filedialog','UserProfiles','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:09460c08-ae1e-4a4e-a0f6-4aee7daa1e5a','guid:activedirectory','GUID_PROGRAM_DATA_CONTAINER_W','','','\0','2019-03-18 22:03:31','2019-03-18 22:03:37');
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:0ac0837c-bbf8-452a-850d-79d08e667ca7','guid:filedialog','Computer','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:0f214138-b1d3-4a90-bba9-27cbc0c5389a','guid:filedialog','SyncSetup','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:15ca69b3-30ee-49c1-ace1-6b5ec372afb5','guid:filedialog','SamplePlaylists','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:1777f761-68ad-4d8a-87bd-30b759fa33dd','guid:filedialog','Favorites','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:18989b1d-99b5-455b-841c-ab7c74e4ddfc','guid:filedialog','Videos','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:18e2ea80-684f-11d2-b9aa-00c04f79f805','guid:activedirectory','GUID_DELETED_OBJECTS_CONTAINER_W','','','\0','2019-03-18 22:01:00','2019-03-18 22:01:11');
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:190337d1-b8ca-4121-a639-6d472d16972a','guid:filedialog','SearchHome','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:1ac14e77-02e7-4e5d-b744-2eb1ae5198b7','guid:filedialog','System','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:1eb93889-e40c-45df-9f0c-64d23bbb6237','guid:activedirectory','GUID_MANAGED_SERVICE_ACCOUNTS_CONTAINER_W','','','\0','2019-03-18 22:04:18','2019-03-18 22:04:29');
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:22b70c67-d56e-4efb-91e9-300fca3dc1aa','guid:activedirectory','GUID_FOREIGNSECURITYPRINCIPALS_CONTAINER_W','','','\0','2019-03-18 22:01:47','2019-03-18 22:01:53');
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:2400183a-6185-49fb-a2d8-4a392a602ba3','guid:filedialog','PublicVideos','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:289a9a43-be44-4057-a41b-587a76d7e7f9','guid:filedialog','SyncResults','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:2a00375e-224c-49de-b8d1-440df7ef3ddc','guid:filedialog','LocalizedResourcesDir','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:2b0f765d-c0e9-4171-908e-08a611b84ff6','guid:filedialog','Cookies','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:2c36c0aa-5812-4b87-bfd0-4cd0dfb19b39','guid:filedialog','OriginalImages','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:2fbac187-0ade-11d2-97c4-00c04fd8d5cd','guid:activedirectory','GUID_INFRASTRUCTURE_CONTAINER_W','','','\0','2019-03-18 22:02:01','2019-03-18 22:02:08');
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:3214fab5-9757-4298-bb61-92a9deaa44ff','guid:filedialog','PublicMusic','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:33e28130-4e1e-4676-835a-98395c3bc3bb','guid:filedialog','Pictures','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:352481e8-33be-4251-ba85-6007caedcf9d','guid:filedialog','InternetCache','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:374de290-123f-4565-9164-39c4925e467b','guid:filedialog','Downloads','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:3d644c9b-1fb8-4f30-9b45-f670235f79c0','guid:filedialog','PublicDownloads','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:3eb685db-65f9-4cf6-a03a-e3ef65729f3d','guid:filedialog','RoamingAppData','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:43668bf8-c14e-49b2-97c9-747784d784b7','guid:filedialog','SyncManager','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:4bd8d571-6d19-48d3-be97-422220080e43','guid:filedialog','Music','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:4bfefb45-347d-4006-a5be-ac0cb0567192','guid:filedialog','Conflict','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:4c5c32ff-bb9d-43b0-b5b4-2d72e54eaaa4','guid:filedialog','SavedGames','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:4d9f7874-4e0c-4904-967b-40b0d20c3e4b','guid:filedialog','Internet','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:52a4f021-7b75-48a9-9f6b-4b87a210bc8f','guid:filedialog','QuickLaunch','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:56784854-c6cb-462b-8169-88e350acb882','guid:filedialog','Contacts','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:5b3749ad-b49f-49c1-83eb-15370fbd4882','guid:filedialog','TreeProperties','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:5e6c858f-0e22-4760-9afe-ea3317b67173','guid:filedialog','Profile','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:6227f0af-1fc2-410d-8e3b-b10615bb5b0f','guid:activedirectory','GUID_NTDS_QUOTAS_CONTAINER_W','','','\0','2019-03-18 22:03:15','2019-03-18 22:03:22');
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:625b53c3-ab48-4ec1-ba1f-a1ef4146fc19','guid:filedialog','StartMenu','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:62ab5d82-fdc1-4dc3-a9dd-070d1d495d97','guid:filedialog','ProgramData','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:6365d5a7-0f0d-45e5-87f6-0da56b6a4f7d','guid:filedialog','ProgramFilesCommonX64','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:69d2cf90-fc33-4fb7-9a0c-ebb0f0fcb43c','guid:filedialog','PhotoAlbums','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:6d809377-6af0-444b-8957-a3773f02200e','guid:filedialog','ProgramFilesX64','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:6f0cd92b-2e97-45d1-88ff-b0d186b8dedd','guid:filedialog','Connections','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:724ef170-a42d-4fef-9f26-b60e846fba4f','guid:filedialog','AdminTools','','','\0','2019-03-18 21:46:30','2019-03-18 21:46:39');
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:76fc4e2d-d6ad-4519-a663-37bd56068185','guid:filedialog','Printers','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:7b396e54-9ec5-4300-be0a-2482ebae1a26','guid:filedialog','SidebarDefaultParts','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:7c5a40ef-a0fb-4bfc-874a-c0f2e0b9fa8e','guid:filedialog','ProgramFilesX86','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:7d1d3a04-debb-4115-95cf-2f29da2920da','guid:filedialog','SavedSearches','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:82a5ea35-d9cd-47c5-9629-e15d2f714e6e','guid:filedialog','CommonStartup','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:82a74aeb-aeb4-465c-a014-d097ee346d63','guid:filedialog','ControlPanel','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:859ead94-2e85-48ad-a71a-0969cb56a6cd','guid:filedialog','SampleVideos','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:8983036c-27c0-404b-8f08-102d10dcfd74','guid:filedialog','SendTo','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:8ad10c31-2adb-4296-a8f7-e4701232c972','guid:filedialog','ResourceDir','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:905e63b6-c1bf-494e-b29c-65b732d3d21a','guid:filedialog','ProgramFiles','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:9274bd8d-cfd1-41c3-b35e-b13f55a758f4','guid:filedialog','PrintHood','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:98ec0e18-2098-4d44-8644-66979315a281','guid:filedialog','SEARCH_MAPI','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:9e52ab10-f80d-49df-acb8-4330f5687855','guid:filedialog','CDBurning','','','\0','2019-03-18 21:49:02','2019-03-18 21:49:08');
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:a305ce99-f527-492b-8b1a-7e76fa98d6e4','guid:filedialog','AppUpdates','','','\0','2019-03-18 21:48:41','2019-03-18 21:48:48');
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:a361b2ff-ffd2-11d1-aa4b-00c04fd7d83a','guid:activedirectory','GUID_DOMAIN_CONTROLLERS_CONTAINER_W','','','\0','2019-03-18 22:01:31','2019-03-18 22:01:38');
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:a4115719-d62e-491d-aa7c-e74b8be3b067','guid:filedialog','CommonStartMenu','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:a520a1a4-1780-4ff6-bd18-167343c5af16','guid:filedialog','AppDataLow','','','\0','2019-03-18 21:46:51','2019-03-18 21:46:58');
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:a63293e8-664e-48db-a079-df759e0509f7','guid:filedialog','Templates','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:a75d362e-50fc-4fb7-ac2c-a8beaa314493','guid:filedialog','SidebarParts','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:a77f5d77-2e2b-44c3-a6a2-aba601054a51','guid:filedialog','Programs','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:a9d1ca15-7688-11d1-aded-00c04fd8d5cd','guid:activedirectory','GUID_USERS_CONTAINER_W','','','\0','2019-03-18 22:04:04','2019-03-18 22:04:10');
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:aa312825-7688-11d1-aded-00c04fd8d5cd','guid:activedirectory','GUID_COMPUTERS_CONTAINER_W','','','\0','2019-03-18 22:00:29','2019-03-18 22:00:43');
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:ab1d30f3-7688-11d1-aded-00c04fd8d5cd','guid:activedirectory','GUID_SYSTEMS_CONTAINER_W','','','\0','2019-03-18 22:03:47','2019-03-18 22:03:55');
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:ab8153b7-7688-11d1-aded-00c04fd8d5cd','guid:activedirectory','GUID_LOSTANDFOUND_CONTAINER_W','','','\0','2019-03-18 22:02:16','2019-03-18 22:02:22');
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:activedirectory','guid:','Active Directory GUIDs (Example)','<p>More information about these GUIDs <a href=\"https://docs.microsoft.com/en-us/openspecs/windows_protocols/ms-adts/5a00c890-6be5-4575-93c4-8bf8be0ca8d8\">here</a></p>','','\0','2019-03-18 22:00:11','2019-03-18 22:05:29');
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:ae50c081-ebd2-438a-8655-8a092e34987a','guid:filedialog','Recent','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:b250c668-f57d-4ee1-a63c-290ee7d1aa1f','guid:filedialog','SampleMusic','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:b4bfcc3a-db2c-424c-b029-7fe99a87c641','guid:filedialog','Desktop','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:b6ebfb86-6907-413c-9af7-4fc2abf07cc5','guid:filedialog','PublicPictures','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:b7534046-3ecb-4c18-be4e-64cd4cb7d6ac','guid:filedialog','RecycleBin','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:b94237e7-57ac-4347-9151-b08c6c32d1f7','guid:filedialog','CommonTemplates','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:b97d20bb-f46a-4c97-ba10-5e3608430854','guid:filedialog','Startup','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:bd85e001-112e-431e-983b-7b15ac09fff1','guid:filedialog','RecordedTV','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:bfb9d5e0-c6a9-404c-b2b2-ae6db6af4968','guid:filedialog','Links','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:c1bae2d0-10df-4334-bedd-7aa20b227a9d','guid:filedialog','CommonOEMLinks','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:c4900540-2379-4c75-844b-64e6faf8716b','guid:filedialog','SamplePictures','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:c4aa340d-f20f-4863-afef-f87ef2e6ba25','guid:filedialog','PublicDesktop','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:c5abbf53-e17f-4121-8900-86626fc2c973','guid:filedialog','NetHood','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:cac52c1a-b53d-4edc-92d7-6b2e8ac19434','guid:filedialog','Games','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:d0384e7d-bac3-4797-8f14-cba229b392b5','guid:filedialog','CommonAdminTools','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:d20beec4-5ca8-4905-ae3b-bf251ea09b53','guid:filedialog','Network','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:d65231b0-b2f1-4857-a4ce-a8e7c6ea7d27','guid:filedialog','SystemX86','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:d9dc8a3b-b784-432e-a781-5a1130a75963','guid:filedialog','History','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:de61d971-5ebc-4f02-a3a9-6c82895e5c04','guid:filedialog','AddNewPrograms','','','\0','2019-03-18 21:45:41','2019-03-18 21:45:56');
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:de92c1c7-837f-4f69-a3bb-86e631204a23','guid:filedialog','Playlists','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:de974d24-d9c6-4d3e-bf91-f4455120b917','guid:filedialog','ProgramFilesCommonX86','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:debf2536-e1a8-4c59-b6a2-414586476aea','guid:filedialog','PublicGameTasks','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:df7266ac-9274-4867-8d55-3bd661de872d','guid:filedialog','ChangeRemovePrograms','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:dfdf76a2-c82a-4d63-906a-5644ac457385','guid:filedialog','Public','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:ed4824af-dce4-45a8-81e2-fc7965083634','guid:filedialog','PublicDocuments','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:ee32e446-31ca-4aba-814f-a5ebd2fd6d5e','guid:filedialog','SEARCH_CSC','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:f1b32785-6fba-4fcf-9d55-7b8e7f157091','guid:filedialog','LocalAppData','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:f38bf404-1d43-42f2-9305-67de0b28fc23','guid:filedialog','Windows','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:f3ce0f7c-4901-4acc-8648-d5d44b04ef8f','guid:filedialog','UsersFiles','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:f4be92a4-c777-485e-878e-9421d53087db','guid:activedirectory','GUID_MICROSOFT_PROGRAM_DATA_CONTAINER_W','','','\0','2019-03-18 22:02:34','2019-03-18 22:03:06');
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:f7f1ed05-9f6d-47a2-aaae-29d317c6f066','guid:filedialog','ProgramFilesCommon','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:fd228cb7-ae11-4ae3-864c-16f3910ab8fe','guid:filedialog','Fonts','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:fdd39ad0-238f-46af-adb4-6c85480369c7','guid:filedialog','Documents','',NULL,'\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('guid:filedialog','guid:','File Dialog Custom Places (Example)','<p>This is an example for a category of GUIDs.</p>\n<p>See full list <a href=\"https://docs.microsoft.com/en-us/dotnet/framework/winforms/controls/known-folder-guids-for-file-dialog-custom-places\">here</a></p>','','\0','2019-03-18 21:44:36','2019-03-18 21:45:27');
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('ipv4:37.48.104.196/32','ipv4:','ViaThinkSoft Server (Example)','<p><strong>ViaThinkSoft Server<br /></strong></p>\n<p>This object is only included as an example.<br />Please delete this object once your database goes online.</p>','serveradmin@viathinksoft.de','\0',NULL,'2019-04-02 09:08:01');
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('ipv6:2001:1af8:4700:a07e:1::/112','ipv6:','ViaThinkSoft Server (Example)','<p><strong>ViaThinkSoft Server<br /></strong></p>\n<p>This object is only included as an example.<br />Please delete this object once your database goes online.</p>','serveradmin@viathinksoft.de','\0',NULL,'2019-04-02 10:18:54');
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('ipv6:2001:1af8:4700:a07e:1::1337','ipv6:2001:1af8:4700:a07e:1::/112','Main IPv6 address','<p>This address (1337) is used for all internal and external services and domains.</p>','serveradmin@viathinksoft.de','\0',NULL,'2019-03-10 22:43:01');
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('java:com.example','java:','Example domain','<p>This domain is only included as an example.<br />Please delete this object once your database goes online.</p>','','\0','2018-09-30 22:47:59',NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('java:com.example.yourproject','java:com.example','Example project','','','\0',NULL,'2019-03-03 19:09:27');
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('java:com.example.yourproject.enums','java:com.example.yourproject','','','','\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('java:com.example.yourproject.enums.colors','java:com.example.yourproject.enums','','','','\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('java:com.example.yourproject.enums.fruits','java:com.example.yourproject.enums','','','','\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('java:com.example.yourproject.exceptions','java:com.example.yourproject','','','','\0',NULL,NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('java:com.example.yourproject.exceptions.ItemNotFoundException','java:com.example.yourproject.exceptions','','','','\0','2019-03-13 14:43:02',NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('oid:1.3.6.1.4.1.37553.8.32488192274','oid:','WEID Example','<p>This OID/WEID can be used by anyone for the purpose of documentation and examples, in the same way \"example.com\" is defined as an example for website.</p>','example@webfan.de','\0','2019-03-30 19:52:24','2019-04-02 08:46:54');
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('oid:2.999','oid:','Example','<p><strong>This is the official example OID</strong></p>\n<p>This OID is only included as an example.<br />Please delete this object once your database goes online.</p>\n<p>Description by <a href=\"http://oid-info.com/cgi-bin/display?oid=2.999&amp;action=display\">oid-info.com</a> :</p>\n<p><i>This OID can be used by anyone, without any permission, for the purpose of documenting examples of object identifiers (in the same way as \"example.com\" is defined in&nbsp;<a href=\"http://tools.ietf.org/html/rfc2606.html\">IETF RFC 2606</a>&nbsp;as an example for web sites).</i></p>','oid@example.com','\0','2018-09-30 22:49:31','2019-03-18 10:46:17');
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('oid:2.999.1','oid:2.999','','','oid@example.com','\0','2019-04-02 09:08:34',NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('oid:2.999.1.123','oid:2.999.1','','','oid@example.org','\0','2019-04-02 09:09:11',NULL);
INSERT INTO `objects` (id, parent, title, description, ra_email, confidential, created, updated) VALUES ('oid:2.999.2','oid:2.999','','','oid@example.com','\0','2019-04-02 09:08:41',NULL);
