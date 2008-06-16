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
                        <webuijsf:tabSet id="loginTabSet" selected="loginTab" style="height: 214px; left: 48px; top: 48px; position: absolute; width: 310px">
                            <webuijsf:tab actionExpression="#{Login.loginTab_action}" id="loginTab" text="Login">
                                <webuijsf:panelLayout id="layoutPanel1" style="height: 179px; position: relative; width: 311px; -rave-layout: grid">
                                    <webuijsf:label id="label1" style="height: 24px; left: 24px; top: 48px; position: absolute; width: 70px" text="Username"/>
                                    <webuijsf:label id="label2" style="height: 24px; left: 24px; top: 83px; position: absolute; width: 72px" text="Passwort"/>
                                    <webuijsf:textField id="usernameTextField" style="position: absolute; left: 120px; top: 48px; width: 120px; height: 24px" text="#{Login.username}"/>
                                    <webuijsf:passwordField id="passwordTextField" password="#{Login.password}" style="height: 24px; left: 120px; top: 83px; position: absolute; width: 120px"/>
                                    <webuijsf:button actionExpression="#{Login.loginButton_action}" id="loginButton"
                                        style="height: 24px; left: 119px; top: 131px; position: absolute; width: 96px" text="Login"/>
                                    <webuijsf:label id="statusLabel" style="height: 24px; left: 24px; top: 11px; position: absolute; width: 96px"
                                        styleClass="errorMessage" text="#{Login.status}"/>
                                </webuijsf:panelLayout>
                            </webuijsf:tab>
                            <webuijsf:tab actionExpression="#{Login.bookingTab_action}" id="bookingTab" text="Booking">
                                <webuijsf:panelLayout id="layoutPanel2" style="height: 177px; position: relative; width: 311px; -rave-layout: grid">
                                    <webuijsf:button actionExpression="#{Login.kommenPushButton_action}" id="kommenPushButton"
                                        style="height: 24px; left: 23px; top: 120px; position: absolute; width: 96px" text="Kommen"/>
                                    <webuijsf:button actionExpression="#{Login.gehenPushButton_action}" id="gehenPushButton"
                                        style="height: 24px; left: 143px; top: 120px; position: absolute; width: 96px" text="Gehen"/>
                                    <webuijsf:label id="label3" style="height: 24px; left: 24px; top: 33px; position: absolute; width: 70px" text="Username"/>
                                    <webuijsf:label id="label4" style="height: 24px; left: 24px; top: 72px; position: absolute; width: 70px" text="Passwort"/>
                                    <webuijsf:textField id="bookingUsernameTextField"
                                        style="height: 24px; left: 120px; top: 33px; position: absolute; width: 120px" text="#{Login.username}"/>
                                    <webuijsf:passwordField id="bookingPasswordTextFieldt" password="#{Login.password}" style="position: absolute; left: 120px; top: 72px; width: 48px; height: 24px"/>
                                </webuijsf:panelLayout>
                            </webuijsf:tab>
                        </webuijsf:tabSet>
                    </webuijsf:form>
                </webuijsf:body>
            </webuijsf:html>
        </webuijsf:page>
    </f:view>
</jsp:root>
