package utils;

import javax.mail.Authenticator;
import javax.mail.PasswordAuthentication;


/**
 * Generates a PasswordAuthentication object for sendMail
 * @author Popeye
 */
class MailAuthenticator extends Authenticator
{
    private final String username;
    private final String password;

    public MailAuthenticator(String username, String password)
    {
        this.username = username;
        this.password = password;
    }
    
    @Override
    protected PasswordAuthentication getPasswordAuthentication()
    {
        return new PasswordAuthentication(this.username, this.password); 
    }
    
}