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
                        <webuijsf:tabSet binding="#{secure$TTSystem.ttSystemTabSet}" id="ttSystemTabSet" selected="buchungenTab" style="height: 430px; left: 48px; top: 72px; position: absolute; width: 550px">
                            <webuijsf:tab actionExpression="#{secure$TTSystem.buchungenTab_action}" id="buchungenTab" styleClass="tab-header" tabIndex="5" text="Buchungen">
                                <webuijsf:panelLayout id="layoutPanel1" style="height: 394px; position: relative; width: 551px; -rave-layout: grid" styleClass="tab-content">
                                    <webuijsf:table augmentTitle="false" id="table1" style="height: 173px; left: 24px; top: 72px; position: absolute"
                                        title="Buchungen" width="504">
                                        <webuijsf:tableRowGroup id="tableRowGroup1" rows="10" sourceData="#{secure$TTSystem.bookings}" sourceVar="currentRow">
                                            <webuijsf:tableColumn headerText="Vorname" id="tableColumn1" styleClass="bookingTableColumn1" valign="top" width="466">
                                                <webuijsf:staticText id="staticText3" text="#{currentRow.value['firstname']}"/>
                                            </webuijsf:tableColumn>
                                            <webuijsf:tableColumn headerText="Nachname" id="tableColumn2" styleClass="bookingTableColumn2" valign="top">
                                                <webuijsf:staticText id="staticText4" text="#{currentRow.value['lastname']}"/>
                                            </webuijsf:tableColumn>
                                            <webuijsf:tableColumn headerText="Kommen Buchung" id="tableColumn3" styleClass="bookingTableColumn3" valign="top">
                                                <webuijsf:staticText converter="#{secure$TTSystem.dateTimeConverter}" id="staticText5" text="#{currentRow.value['comeBooking']}"/>
                                            </webuijsf:tableColumn>
                                            <webuijsf:tableColumn headerText="Gehen Buchung" id="tableColumn4" styleClass="bookingTableColumn4" valign="top">
                                                <webuijsf:staticText converter="#{secure$TTSystem.dateTimeConverter}" id="staticText1" text="#{currentRow.value['goBooking']}"/>
                                            </webuijsf:tableColumn>
                                        </webuijsf:tableRowGroup>
                                    </webuijsf:table>
                                    <webuijsf:button actionExpression="#{secure$TTSystem.comePushButton_action}" id="comePushButton"
                                        style="height: 24px; left: 23px; top: 24px; position: absolute; width: 120px" tabIndex="1" text="Kommen"/>
                                    <webuijsf:button actionExpression="#{secure$TTSystem.goPushButton_action}" id="goPushButton"
                                        style="height: 24px; left: 192px; top: 24px; position: absolute; width: 119px" tabIndex="2" text="Gehen"/>
                                </webuijsf:panelLayout>
                            </webuijsf:tab>
                            <webuijsf:tab actionExpression="#{secure$TTSystem.einstellungenTab_action}" id="einstellungenTab" styleClass="tab-header"
                                tabIndex="6" text="Einstellungen">
                                <webuijsf:panelLayout id="layoutPanel2" style="height: 203px; position: relative; width: 100%; -rave-layout: grid" styleClass="tab-content">
                                    <webuijsf:label id="newPasswordLabel" style="height: 24px; left: 24px; top: 96px; position: absolute; width: 96px" text=" Passwort"/>
                                    <webuijsf:button actionExpression="#{secure$TTSystem.changePasswordButton_action}" id="changePasswordButton"
                                        style="height: 24px; left: 143px; top: 136px; position: absolute; width: 192px" tabIndex="3" text="Änderungen übernehmen"/>
                                    <webuijsf:passwordField columns="25" id="passwordTextField" password="#{SessionBean1.user.password}"
                                        style="height: 24px; left: 144px; top: 96px; position: absolute; width: 168px" tabIndex="2"/>
                                    <webuijsf:label id="oldPasswordLabel" style="height: 24px; left: 24px; top: 57px; position: absolute; width: 96px" text="Benutzername"/>
                                    <webuijsf:label id="label1" style="height: 24px; left: 24px; top: 11px; position: absolute; width: 264px" text="#{secure$TTSystem.status}"/>
                                    <webuijsf:textField columns="25" id="textField1"
                                        style="height: 24px; left: 144px; top: 58px; position: absolute; width: 168px" text="#{SessionBean1.user.username}"/>
                                </webuijsf:panelLayout>
                            </webuijsf:tab>
                        </webuijsf:tabSet>
                        <webuijsf:button actionExpression="#{secure$TTSystem.logoutButton_action}" id="logoutButton"
                            style="position: absolute; left: 48px; top: 24px; width: 120px; height: 24px" tabIndex="4" text="Logout"/>
                        <webuijsf:bubble autoClose="false" id="bubble1" style="height: 118px; left: 72px; top: 120px; position: absolute" title="Hinweis!"
                            visible="#{secure$TTSystem.message}" width="166">
                            <webuijsf:panelLayout id="layoutPanel3" style="height: 72px; position: relative; width: 180px; -rave-layout: grid">
                                <webuijsf:button actionExpression="#{secure$TTSystem.bookPushButton_action}" id="button3"
                                    style="height: 24px; left: -1px; top: 48px; position: absolute; width: 72px" text="Ja"/>
                                <webuijsf:button actionExpression="#{secure$TTSystem.dontBookPushButton_action}" id="button1"
                                    style="height: 24px; left: 95px; top: 48px; position: absolute; width: 72px" text="Nein"/>
                                <webuijsf:staticText id="staticText1" style="left: 0px; top: 0px; position: absolute" text="Buchung auser Takt! Trotzdem buchen?"/>
                            </webuijsf:panelLayout>
                        </webuijsf:bubble>
                    </webuijsf:form>
                </webuijsf:body>
            </webuijsf:html>
        </webuijsf:page>
    </f:view>
</jsp:root>
