<?xml version="1.0" encoding="UTF-8"?>
<!-- 
Document   : Page1
Created on : 16.06.2008, 14:24:26
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
                        <webuijsf:tabSet id="loginTabSet" selected="bookingTab" style="height: 238px; left: 48px; top: 48px; position: absolute; width: 310px">
                            <webuijsf:tab actionExpression="#{Login.loginTab_action}" id="loginTab" tabIndex="4" text="Login">
                                <webuijsf:panelLayout id="layoutPanel1" style="height: 203px; position: relative; width: 311px; -rave-layout: grid">
                                    <webuijsf:label id="label1" style="height: 24px; left: 24px; top: 48px; position: absolute; width: 94px" text="Benutzername"/>
                                    <webuijsf:label id="label2" style="height: 24px; left: 24px; top: 83px; position: absolute; width: 94px" text="Passwort"/>
                                    <webuijsf:textField id="usernameTextField" style="position: absolute; left: 120px; top: 48px; width: 120px; height: 24px"
                                        tabIndex="1" text="#{Login.username}"/>
                                    <webuijsf:passwordField id="passwordTextField" password="#{Login.password}"
                                        style="height: 24px; left: 120px; top: 83px; position: absolute; width: 120px" tabIndex="2"/>
                                    <webuijsf:button actionExpression="#{Login.loginButton_action}" id="loginButton"
                                        style="height: 24px; left: 119px; top: 131px; position: absolute; width: 96px" tabIndex="3" text="Login"/>
                                    <webuijsf:label id="statusLabel" style="height: 24px; left: 24px; top: 10px; position: absolute; width: 262px"
                                        styleClass="errorMessage" text="#{Login.status}"/>
                                    <webuijsf:label id="label5"
                                        style="font-weight: normal; height: 22px; left: 24px; top: 168px; position: absolute; width: 118px" text="Passwort vergessen?"/>
                                    <webuijsf:hyperlink actionExpression="#{Login.newPasswordHyperlink_action}" id="newPasswordHyperlink"
                                        style="height: 22px; left: 144px; top: 168px; position: absolute; width: 70px" text="hier klicken"/>
                                </webuijsf:panelLayout>
                            </webuijsf:tab>
                            <webuijsf:tab actionExpression="#{Login.bookingTab_action}" id="bookingTab" tabIndex="5" text="Buchen">
                                <webuijsf:panelLayout id="layoutPanel2" style="height: 177px; position: relative; width: 311px; -rave-layout: grid">
                                    <webuijsf:button actionExpression="#{Login.comePushButton_action}" id="comePushButton"
                                        style="height: 24px; left: 23px; top: 131px; position: absolute; width: 96px" tabIndex="3" text="Kommen"/>
                                    <webuijsf:button actionExpression="#{Login.goPushButton_action}" id="goPushButton"
                                        style="height: 24px; left: 143px; top: 131px; position: absolute; width: 96px" tabIndex="4" text="Gehen"/>
                                    <webuijsf:label id="label3" style="height: 24px; left: 24px; top: 48px; position: absolute; width: 94px" text="Benutzername"/>
                                    <webuijsf:label id="label4" style="height: 24px; left: 24px; top: 83px; position: absolute; width: 94px" text="Passwort"/>
                                    <webuijsf:textField id="bookingUsernameTextField"
                                        style="height: 24px; left: 120px; top: 48px; position: absolute; width: 120px" tabIndex="1" text="#{Login.username}"/>
                                    <webuijsf:passwordField id="bookingPasswordTextField" password="#{Login.password}"
                                        style="height: 24px; left: 120px; top: 83px; position: absolute; width: 48px" tabIndex="2"/>
                                    <webuijsf:label id="statusLabel1" style="height: 24px; left: 24px; top: 10px; position: absolute; width: 262px"
                                        styleClass="errorMessage" text="#{Login.status}"/>
                                </webuijsf:panelLayout>
                            </webuijsf:tab>
                        </webuijsf:tabSet>
                        <webuijsf:bubble autoClose="false" id="bubble1" style="height: 118px; left: 72px; top: 120px; position: absolute" title="Hinweis!"
                            visible="#{Login.message}" width="166">
                            <webuijsf:panelLayout id="layoutPanel3" style="height: 72px; position: relative; width: 180px; -rave-layout: grid">
                                <webuijsf:button actionExpression="#{Login.bookPushButton_action}" id="button3"
                                    style="height: 24px; left: -1px; top: 48px; position: absolute; width: 72px" text="Ja"/>
                                <webuijsf:button actionExpression="#{Login.dontBookPushButton_action}" id="button1"
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
