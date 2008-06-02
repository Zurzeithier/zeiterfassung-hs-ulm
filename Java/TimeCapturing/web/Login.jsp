<?xml version="1.0" encoding="UTF-8"?>
<!-- 
    Document   : Page1
    Created on : 31.05.2008, 00:19:45
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
                        <webuijsf:tabSet id="loginTabSet" selected="changePWDTab" style="height: 262px; left: 48px; top: 48px; position: absolute; width: 406px">
                            <webuijsf:tab actionExpression="#{Login.loginTab_action}" id="loginTab" text="Login">
                                <webuijsf:panelLayout id="loginLayoutPanel" style="width: 100%; height: 128px;">
                                    <webuijsf:label for="loginUsernameTextField" id="loginUsernameLabel"
                                        style="height: 24px; left: 24px; top: 48px; position: absolute; width: 94px" text="Username"/>
                                    <webuijsf:label for="loginPWDTextField" id="loginPWDLabel"
                                        style="height: 24px; left: 24px; top: 83px; position: absolute; width: 96px" text="Password"/>
                                    <webuijsf:textField id="loginUsernameTextField"
                                        style="height: 24px; left: 120px; top: 47px; position: absolute; width: 120px" tabIndex="1" text="#{Login.userBean.username}"/>
                                    <webuijsf:passwordField id="loginPWDTextField" password="#{Login.userBean.password}"
                                        style="height: 24px; left: 120px; top: 82px; position: absolute; width: 120px" tabIndex="2"/>
                                    <webuijsf:button actionExpression="#{Login.loginButton_action}" id="loginButton"
                                        style="height: 24px; left: 119px; top: 120px; position: absolute; width: 119px" tabIndex="3" text="Login"/>
                                    <webuijsf:label id="statusLoginLabel" style="height: 24px; left: 24px; top: 10px; position: absolute; width: 264px"
                                        styleClass="errorMessage" text="#{Login.statusLogin}"/>
                                </webuijsf:panelLayout>
                            </webuijsf:tab>
                            <webuijsf:tab actionExpression="#{Login.changePWDTab_action}" id="changePWDTab" text="Change Password">
                                <webuijsf:panelLayout id="changePWDLayoutPanel" style="height: 227px; position: relative; width: 383px; -rave-layout: grid">
                                    <webuijsf:hyperlink actionExpression="#{Login.forgotPasswordHyperlink_action}" id="forgotPasswordHyperlink"
                                        style="left: 144px; top: 192px; position: absolute" tabIndex="5" text="click here" toolTip="If you forgot your Password"/>
                                    <webuijsf:textField id="textField2" style="left: 120px; top: 48px; position: absolute" tabIndex="1" text="#{Login.userBean.username}"/>
                                    <webuijsf:label for="loginUsernameTextField" id="changePWDUsernameLabel"
                                        style="height: 24px; left: 24px; top: 48px; position: absolute; width: 94px" text="Username"/>
                                    <webuijsf:label for="loginPWDTextField" id="changePWDNewPWDLabel"
                                        style="height: 24px; left: 24px; top: 120px; position: absolute; width: 96px" text="new  Password"/>
                                    <webuijsf:passwordField id="passwordField2" password="#{Login.newPassword}"
                                        style="left: 120px; top: 120px; position: absolute" tabIndex="3"/>
                                    <webuijsf:passwordField id="passwordField3" password="#{Login.userBean.password}"
                                        style="left: 120px; top: 83px; position: absolute" tabIndex="2"/>
                                    <webuijsf:label for="loginPWDTextField" id="changePWDOldPWDLabel"
                                        style="height: 24px; left: 24px; top: 83px; position: absolute; width: 96px" text="Old Password"/>
                                    <webuijsf:button actionExpression="#{Login.changePwdButton_action}" id="changePwdButton"
                                        style="height: 24px; left: 119px; top: 155px; position: absolute; width: 120px" tabIndex="4" text="Change Password"/>
                                    <webuijsf:label id="statusChangePwdLabel" style="height: 24px; left: 24px; top: 10px; position: absolute; width: 264px"
                                        styleClass="errorMessage" text="#{Login.statusChangePWD}"/>
                                    <webuijsf:label id="label8" style="height: 24px; left: 24px; top: 192px; position: absolute; width: 118px" text="forgot password? "/>
                                </webuijsf:panelLayout>
                            </webuijsf:tab>
                            <webuijsf:tab actionExpression="#{Login.buchungTab_action}" id="buchungTab" text="Buchung">
                                <webuijsf:panelLayout id="buchungLayoutPanel" style="height: 128px; position: relative; width: 407px; -rave-layout: grid">
                                    <webuijsf:label for="loginUsernameTextField" id="buchungUsernameLabel"
                                        style="height: 24px; left: 0px; top: 24px; position: absolute; width: 70px" text="Username"/>
                                    <webuijsf:textField columns="15" id="buchungUsernameTextField" style="left: 72px; top: 24px; position: absolute" text="#{Login.userBean.username}"/>
                                    <webuijsf:label for="loginPWDTextField" id="buchungPWDLabel"
                                        style="height: 24px; left: 216px; top: 24px; position: absolute; width: 70px" text="Password"/>
                                    <webuijsf:passwordField columns="15" id="buchungPasswordTextField" password="#{Login.userBean.password}" style="left: 288px; top: 24px; position: absolute"/>
                                    <webuijsf:button actionExpression="#{Login.buchungKommenButton_action}" id="buchungKommenButton"
                                        style="height: 24px; left: -1px; top: 72px; position: absolute; width: 168px" text="Kommen"/>
                                    <webuijsf:button actionExpression="#{Login.buchungGehenButton_action}" id="buchungGehenButton"
                                        style="height: 24px; left: 215px; top: 72px; position: absolute; width: 168px" text="Gehen"/>
                                </webuijsf:panelLayout>
                            </webuijsf:tab>
                        </webuijsf:tabSet>
                        <webuijsf:menu id="menu1" items="#{Login.menu1DefaultOptions.options}" style="position: absolute; left: 96px; top: 360px; width: 240px; height: 48px"/>
                        <webuijsf:menu id="menu2" items="#{Login.menu2DefaultOptions.options}" style="position: absolute; left: 264px; top: 384px"/>
                    </webuijsf:form>
                </webuijsf:body>
            </webuijsf:html>
        </webuijsf:page>
    </f:view>
</jsp:root>
