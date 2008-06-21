package application.data_beans;

/**
 *
 * @author Popeye
 */
public class MailData
{
    public final String address;
    public final String user;
    public final String password;
    
    /**
     * 
     * @param adress
     * @param user
     * @param password
     */
    public MailData(String adress, String user, String password)
    {
        this.address = adress;
        this.user = user;
        this.password = password;
    }
}
