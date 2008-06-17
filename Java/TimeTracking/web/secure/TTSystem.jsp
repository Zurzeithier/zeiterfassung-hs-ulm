<?xml version="1.0" encoding="UTF-8"?>
<!-- 
    Document   : TTSystem
    Created on : 17.06.2008, 17:56:00
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
                        <webuijsf:tabSet id="tabSet1" selected="tab1" style="height: 334px; left: 48px; top: 48px; position: absolute; width: 454px">
                            <webuijsf:tab id="tab1" text="Buchungen">
                                <webuijsf:panelLayout id="layoutPanel1" style="height: 249px; position: relative; width: 100%; -rave-layout: grid">
                                    <webuijsf:table augmentTitle="false" id="table1"
                                        style="height: 197px; left: 24px; top: 72px; position: absolute; width: 408px" title="Buchungen" width="408">
                                        <webuijsf:tableRowGroup id="tableRowGroup1" rows="10" sourceData="#{secure$TTSystem.bookings}" sourceVar="currentRow">
                                            <webuijsf:tableColumn headerText="Mid" id="tableColumn1" valign="top">
                                                <webuijsf:staticText id="staticText1" text="#{currentRow.value['mid']}"/>
                                            </webuijsf:tableColumn>
                                            <webuijsf:tableColumn headerText="Username" id="tableColumn2" valign="top">
                                                <webuijsf:staticText id="staticText2" text="#{currentRow.value['username']}"/>
                                            </webuijsf:tableColumn>
                                            <webuijsf:tableColumn headerText="Firstname" id="tableColumn3" valign="top">
                                                <webuijsf:staticText id="staticText3" text="#{currentRow.value['firstname']}"/>
                                            </webuijsf:tableColumn>
                                        </webuijsf:tableRowGroup>
                                    </webuijsf:table>
                                </webuijsf:panelLayout>
                            </webuijsf:tab>
                            <webuijsf:tab id="tab2" text="Einstellungen">
                                <webuijsf:panelLayout id="layoutPanel2" style="height: 249px; position: relative; width: 100%; -rave-layout: grid"/>
                            </webuijsf:tab>
                        </webuijsf:tabSet>
                    </webuijsf:form>
                </webuijsf:body>
            </webuijsf:html>
        </webuijsf:page>
    </f:view>
</jsp:root>
