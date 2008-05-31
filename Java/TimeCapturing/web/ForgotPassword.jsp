<?xml version="1.0" encoding="UTF-8"?>
<!-- 
    Document   : ForgotPassword
    Created on : 31.05.2008, 13:48:27
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
                        <webuijsf:label for="textField1" id="label1" style="height: 22px; left: 72px; top: 48px; position: absolute; width: 70px" text="Username"/>
                        <webuijsf:textField id="textField1" style="position: absolute; left: 144px; top: 48px; width: 120px; height: 24px"/>
                        <webuijsf:button id="button1" style="position: absolute; left: 144px; top: 96px; width: 96px; height: 24px" text="Send Password"/>
                    </webuijsf:form>
                </webuijsf:body>
            </webuijsf:html>
        </webuijsf:page>
    </f:view>
</jsp:root>
