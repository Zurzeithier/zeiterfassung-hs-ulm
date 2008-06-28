<?xml version="1.0" encoding="UTF-8"?>
<!-- 
    Document   : ForgotPassword
    Created on : 21.06.2008, 19:21:24
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
                        <webuijsf:textField columns="25" id="textField1" style="height: 24px; left: 144px; top: 72px; position: absolute; width: 168px" text="#{ForgotPassword.username}"/>
                        <webuijsf:label id="label1" style="height: 24px; left: 48px; top: 72px; position: absolute; width: 94px" text="Benutzername"/>
                        <webuijsf:button actionExpression="#{ForgotPassword.sendNewPassword_action}" id="sendNewPassword"
                            style="height: 24px; left: 143px; top: 120px; position: absolute; width: 192px" styleClass="button" text="neues Passwort zusenden"/>
                        <webuijsf:label id="statusLabel1" style="height: 24px; left: 48px; top: 24px; position: absolute; width: 262px"
                            styleClass="errorMessage" text="#{ForgotPassword.status}"/>
                    </webuijsf:form>
                </webuijsf:body>
            </webuijsf:html>
        </webuijsf:page>
    </f:view>
</jsp:root>
