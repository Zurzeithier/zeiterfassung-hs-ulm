package utils;

import java.util.Date;
import java.util.Properties;
import javax.mail.Message;
import javax.mail.Session;
import javax.mail.Transport;
import javax.mail.internet.InternetAddress;
import javax.mail.internet.MimeMessage;

/**
 * Provides methods for mail processing
 * @author Popeye
 */
public class MailUtils
{

    /**
     * Sends a mail using an external server 
     * @param address
     * @param user
     * @param password
     * @param transmitter
     * @param receiver
     * @param subject
     * @param content
     * @return Whether the operation was successful
     */
    public static boolean sendMail(String address, String user, String password, String transmitter, String receiver, String subject, String content)
    {
        MailAuthenticator authentication = new MailAuthenticator(user, password);

        // New mail session
        Properties mailServer = new Properties();
        mailServer.put("mail.smtp.host", address);
        mailServer.put("mail.smtp.auth", "true");
        Session mailSession = Session.getDefaultInstance(mailServer, authentication);

        try
        {
            // Create message
            Message msg = new MimeMessage(mailSession);
            msg.setFrom(new InternetAddress(receiver));
            msg.setRecipient(Message.RecipientType.TO, new InternetAddress(transmitter));
            msg.setSubject(subject);
            msg.setText(content);

            //msg.setHeader("Test", "Test");
            msg.setSentDate(new Date());

            // Send message
            Transport.send(msg);

            return true;
        }
        catch (Exception e)
        {
            return false;
        }
    }

    /**
     * Generates a new password
     * @param length
     * @return Password string
     */
    public static String genertateRandomPassword(int length)
    {
        StringBuilder pwd = new StringBuilder();
        int randomNumber = 0;
        String chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

        for (int i = 0; i < length; i++)
        {
             // get random number
            randomNumber = (int) Math.round(Math.random() * chars.length());
            pwd.append(chars.charAt(randomNumber));
        }

        return pwd.toString();
    }

}
