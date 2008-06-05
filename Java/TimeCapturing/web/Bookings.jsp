<?xml version="1.0" encoding="UTF-8"?>
<!-- 
    Document   : Bookings
    Created on : 31.05.2008, 00:32:31
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
                    <webuijsf:form id="form1" virtualFormsConfig="">
                        <webuijsf:table augmentTitle="false" id="table1" style="height: 149px; left: 48px; top: 120px; position: absolute; width: 384px"
                            title="Table" width="384">
                            <webuijsf:tableRowGroup id="tableRowGroup1" rows="10" sourceData="#{Bookings.defaultTableDataProvider}" sourceVar="currentRow">
                                <webuijsf:tableColumn binding="#{Bookings.tableColumn1}" headerText="column1" id="tableColumn1" sort="column1">
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
                        <webuijsf:button actionExpression="#{Bookings.logoutButton_action}" id="logoutButton"
                            style="height: 24px; left: 47px; top: 24px; position: absolute; width: 120px" text="Logout"/>
                        <webuijsf:button actionExpression="#{Bookings.kommenButton_action}" id="kommenButton"
                            style="height: 24px; left: 47px; top: 72px; position: absolute; width: 120px" text="Kommen"/>
                        <webuijsf:button actionExpression="#{Bookings.gehenButton_action}" id="gehenButton"
                            style="height: 24px; left: 215px; top: 72px; position: absolute; width: 120px" text="Gehen"/>
                        <webuijsf:label id="label1" style="position: absolute; left: 48px; top: 336px; width: 288px; height: 24px" text="#{Bookings.debug}"/>
                    </webuijsf:form>
                </webuijsf:body>
            </webuijsf:html>
        </webuijsf:page>
    </f:view>
</jsp:root>
