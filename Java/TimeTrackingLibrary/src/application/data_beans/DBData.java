package application.data_beans;

/**
 *
 * Contains the address, username and password
 * for the database 
 * @author manuel, steffen
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
    public DBData(String address, String user, String password)
    {
        this.address = address;
        this.user = user;
        this.password = password;
    }

}
