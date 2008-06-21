package application.data_beans;

/**
 *
 * Contains the address, username and password
 * for the database 
 * @author manuel
 */
public class DBData
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
    public DBData(String adress, String user, String password)
    {
        this.address = adress;
        this.user = user;
        this.password = password;
    }

}
