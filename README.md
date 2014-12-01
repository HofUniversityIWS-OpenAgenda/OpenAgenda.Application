OpenAgenda.Application
======================

* Base application for TYPO3 Flow


URIs
----

**Definitions**

* {@controller}/list.json                                                      OpenAgenda :: All lists: JSON
* meeting/{meeting.__identity}/{@action}.json                                  OpenAgenda :: Meeting: JSON
* task/{task.__identity}/{@action}.json                                        OpenAgenda :: Task: JSON
* template/{controller}/{action}.{format}                                      OpenAgenda :: Template: HTML

**Examples**

* Meeting
  * /meeting/list.json
  * /meeting/{identifier}/show.json
* Task
  * /task/list.json
  * /task/{identifier}/show.json
* Templates
  * /template/Meeting/Index.html *to read from Resource/Private/Templates/Meeting/Index.html*
  
Identifiers can be taken from *'__identity'* properties of the accordant JSON responses.