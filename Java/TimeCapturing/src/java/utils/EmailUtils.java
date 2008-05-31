package utils;

import com.sun.mail.smtp.SMTPAddressFailedException;
import com.sun.mail.smtp.SMTPAddressSucceededException;
import com.sun.mail.smtp.SMTPSendFailedException;
import com.sun.mail.smtp.SMTPTransport;
import java.util.Date;
import java.util.Properties;
import javax.mail.MessagingException;
import javax.mail.SendFailedException;
import javax.mail.internet.InternetAddress;
import javax.mail.internet.MimeMessage;

/**
 *
 * @author manuel
 */
public class EmailUtils
{

    public static void sendMail(String to, String from, String subject, String message, String mailhost, String user, String password)
    {
        // Send Email
        // This clip can be used in a button action
        // method to send an e-mail via SMTP
        // (if no authentication is required from the mailserver)

        // password
        boolean auth = true;
        boolean ssl = false;
        Properties props = System.getProperties();

        if (mailhost != null)
        {
            props.put("mail.smtp.host", mailhost);
        }
        if (auth)
        {
            props.put("mail.smtp.auth", "true");
        }
        // Get a Session object
        javax.mail.Session session = javax.mail.Session.getInstance(props, null);

        // Construct the message
        javax.mail.Message msg = new MimeMessage(session);

        try
        {
            // Set message details
            msg.setFrom(new InternetAddress(from));
            msg.setRecipient(javax.mail.Message.RecipientType.TO, new InternetAddress(to));
            msg.setSubject(subject);
            msg.setSentDate(new Date());
            msg.setText(message);

            // Send the thing off
            SMTPTransport t = (SMTPTransport) session.getTransport(ssl ? "smtps" : "smtp");
            try
            {
                if (auth)
                {
                    t.connect(mailhost, user, password);
                }
                else
                {
                    t.connect();
                }
                t.sendMessage(msg, msg.getAllRecipients());
            }
            finally
            {
                t.close();
            }
        }
        catch (Exception e)
        {
            if (e instanceof SendFailedException)
            {
                MessagingException sfe = (MessagingException) e;
                if (sfe instanceof SMTPSendFailedException)
                {
                    SMTPSendFailedException ssfe = (SMTPSendFailedException) sfe;
                }
                Exception ne;
                while ((ne = sfe.getNextException()) != null && ne instanceof MessagingException)
                {
                    sfe = (MessagingException) ne;
                    if (sfe instanceof SMTPAddressFailedException)
                    {
                        SMTPAddressFailedException ssfe = (SMTPAddressFailedException) sfe;
                    }
                    else if (sfe instanceof SMTPAddressSucceededException)
                    {
                        SMTPAddressSucceededException ssfe = (SMTPAddressSucceededException) sfe;
                    }
                }
            }
            else
            {
            }
        }
    }

}
