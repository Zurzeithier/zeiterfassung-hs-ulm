<?xml version="1.0" encoding="UTF-8"?>
<!-- 
    Document   : TTSystem
    Created on : 16.06.2008, 14:31:57
    Author     : manuel
-->
<jsp:root version="2.1" xmlns:f="http://java.sun.com/jsf/core" xmlns:h="http://java.sun.com/jsf/html" xmlns:jsp="http://java.sun.com/JSP/Page" xmlns:webuijsf="http://www.sun.com/webui/webuijsf">
    <jsp:directive.page contentType="text/html;charset=UTF-8" pageEncoding="UTF-8"/>
    <f:view>
        <webuijsf:page id="page1">
            <webuijsf:html id="html1">
                <webuijsf:head id="head1">
                    <webuijsf:link id="link1" url="/resources/stylesheet.css"/>
                </webuijsf:head>
                <webuijsf:body id="body1" style="-rave-layout: grid">
                    <webuijsf:form id="form1">
                        <webuijsf:tabSet id="tabSet1" selected="tab1" style="height: 310px; left: 24px; top: 24px; position: absolute; width: 454px">
                            <webuijsf:tab id="tab1" text="Booking">
                                <webuijsf:panelLayout id="layoutPanel1" style="width: 100%; height: 128px;">
                                    <webuijsf:table augmentTitle="false" id="table1"
                                        style="height: 144px; left: 24px; top: 24px; position: absolute; width: 216px" title="Table" width="216">
                                        <webuijsf:tableRowGroup id="tableRowGroup1" rows="10" sourceData="#{secure$TTSystem.defaultTableDataProvider}" sourceVar="currentRow">
                                            <webuijsf:tableColumn headerText="Zeile1" id="tableColumn1" sort="column1">
                                                <webuijsf:staticText id="staticText1" text="#{currentRow.value['column1']}"/>
                                            </webuijsf:tableColumn>
                                            <webuijsf:tableColumn headerText="column2" id="tableColumn2" sort="column2">
                                                <webuijsf:staticText id="staticText2" text="#{currentRow.value['column2']}"/>
                                            </webuijsf:tableColumn>
                                            <webuijsf:tableColumn headerText="column3" id="tableColumn3" sort="column3">
                                                <webuijsf:staticText id="staticText3" text="#{currentRow.value['column3']}"/>
                                            </webuijsf:tableColumn>
                                        </webuijsf:tableRowGroup>
                                    </webuijsf:table>
                                </webuijsf:panelLayout>
                            </webuijsf:tab>
                            <webuijsf:tab id="tab2" text="Change User">
                                <webuijsf:panelLayout id="layoutPanel2" style="width: 100%; height: 128px;"/>
                            </webuijsf:tab>
                        </webuijsf:tabSet>
                    </webuijsf:form>
                </webuijsf:body>
            </webuijsf:html>
        </webuijsf:page>
    </f:view>
</jsp:root>