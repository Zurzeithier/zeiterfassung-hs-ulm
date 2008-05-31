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
                        <webuijsf:tabSet id="tabSet1" selected="tab1" style="height: 262px; left: 48px; top: 48px; position: absolute; width: 406px">
                            <webuijsf:tab id="loginTab" text="Login">
                                <webuijsf:panelLayout id="layoutPanel1" style="width: 100%; height: 128px;">
                                    <webuijsf:label for="textField1" id="label1" style="height: 24px; left: 24px; top: 48px; position: absolute; width: 94px" text="Username"/>
                                    <webuijsf:label for="passwordField1" id="label2"
                                        style="height: 24px; left: 24px; top: 83px; position: absolute; width: 96px" text="Password"/>
                                    <webuijsf:textField id="textField1" style="height: 24px; left: 120px; top: 47px; position: absolute; width: 120px" text="#{Login.userBean.username}"/>
                                    <webuijsf:passwordField id="passwordField1" password="#{Login.userBean.password}" style="height: 24px; left: 120px; top: 82px; position: absolute; width: 120px"/>
                                    <webuijsf:button actionExpression="#{Login.loginButton_action}" id="loginButton"
                                        style="height: 24px; left: 119px; top: 120px; position: absolute; width: 119px" text="Login"/>
                                    <webuijsf:label id="statusLoginLabel" style="height: 24px; left: 24px; top: 10px; position: absolute; width: 264px"
                                        styleClass="errorMessage" text="#{Login.statusLogin}"/>
                                </webuijsf:panelLayout>
                            </webuijsf:tab>
                            <webuijsf:tab id="tab2" text="Change Password">
                                <webuijsf:panelLayout id="layoutPanel2" style="height: 227px; position: relative; width: 383px; -rave-layout: grid">
                                    <webuijsf:textField id="textField2" style="left: 120px; top: 48px; position: absolute" text="#{Login.userBean.username}"/>
                                    <webuijsf:label for="textField1" id="label4" style="height: 24px; left: 24px; top: 48px; position: absolute; width: 94px" text="Username"/>
                                    <webuijsf:label for="passwordField1" id="label5"
                                        style="height: 24px; left: 24px; top: 120px; position: absolute; width: 96px" text="new  Password"/>
                                    <webuijsf:passwordField id="passwordField2" password="#{Login.newPassword}" style="left: 120px; top: 120px; position: absolute"/>
                                    <webuijsf:passwordField id="passwordField3" password="#{Login.userBean.password}" style="left: 120px; top: 83px; position: absolute"/>
                                    <webuijsf:label for="passwordField1" id="label3"
                                        style="height: 24px; left: 24px; top: 83px; position: absolute; width: 96px" text="Old Password"/>
                                    <webuijsf:button actionExpression="#{Login.changePwdButton_action}" id="changePwdButton"
                                        style="height: 24px; left: 119px; top: 155px; position: absolute; width: 120px" text="Change Password"/>
                                    <webuijsf:label id="statusChangePwdLabel" style="height: 24px; left: 24px; top: 10px; position: absolute; width: 264px"
                                        styleClass="errorMessage" text="#{Login.statusChangePWD}"/>
                                </webuijsf:panelLayout>
                            </webuijsf:tab>
                            <webuijsf:tab id="tab1" text="Buchung">
                                <webuijsf:panelLayout id="layoutPanel3" style="height: 128px; position: relative; width: 407px; -rave-layout: grid">
                                    <webuijsf:label for="textField1" id="label6" style="height: 24px; left: 0px; top: 24px; position: absolute; width: 70px" text="Username"/>
                                    <webuijsf:textField columns="15" id="buchungUsernameTextField" style="left: 72px; top: 24px; position: absolute" text="#{Login.userBean.username}"/>
                                    <webuijsf:label for="passwordField1" id="label7"
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
