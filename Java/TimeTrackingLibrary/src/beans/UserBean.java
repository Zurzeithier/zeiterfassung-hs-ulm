package beans;

/**
 * Represents one row of the User table
 * @author manuel
 */
public class UserBean
{

    private int mid;
    private String firstname;
    private String name;
    private String username;
    private String password;

    /**
     * Creates a new instance of UserBean
     */
    public UserBean()
    {
    }

    /**
     * 
     * @return firstname
     */
    public String getFirstname()
    {
        return firstname;
    }

    /**
     * 
     * @param firstname
     */
    public void setFirstname(String firstname)
    {
        this.firstname = firstname;
    }

    /**
     * 
     * @return mid
     */
    public int getMid()
    {
        return mid;
    }

    /**
     * 
     * @param mid
     */
    public void setMid(int mid)
    {
        this.mid = mid;
    }

    /**
     * 
     * @return name
     */
    public String getName()
    {
        return name;
    }

    /**
     * 
     * @param name
     */
    public void setName(String name)
    {
        this.name = name;
    }

    /**
     * 
     * @return password
     */
    public String getPassword()
    {
        return password;
    }

    /**
     * 
     * @param password
     */
    public void setPassword(String password)
    {
        this.password = password;
    }

    /**
     * 
     * @return username
     */
    public String getUsername()
    {
        return username;
    }

    /**
     * 
     * @param username
     */
    public void setUsername(String username)
    {
        this.username = username;
    }
    
}
