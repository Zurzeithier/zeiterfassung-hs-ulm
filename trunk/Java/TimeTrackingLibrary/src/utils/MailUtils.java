package utils;

import java.util.Date;
import java.util.Properties;
import javax.mail.Message;
import javax.mail.Session;
import javax.mail.Transport;
import javax.mail.internet.InternetAddress;
import javax.mail.internet.MimeMessage;
/**
 *
 * @author Popeye
 */
public class MailUtils {
    
    public static boolean sendMail(String address, String user, String password, String transmitter, String receiver, String subject, String content)
    {

        /*String address = "chamaeleon-cms.de";
        String transmitter = "hallo@blabla.de";
        String to = "frehse.steffen@googlemail.com";
        String subject = "test";
        String content = "noch mehr test";
        String username = "timetracking@chamaeleon-cms.de";
        String password = "geheim";*/
                                
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
}
