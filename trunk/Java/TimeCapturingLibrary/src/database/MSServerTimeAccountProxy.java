package database;

import beans.TimeAccountBean;

/**
 *
 * @author manuel
 */
public class MSServerTimeAccountProxy extends MSServer implements TimeAccountProxy
{
    public MSServerTimeAccountProxy(String adress, String username, String password)
    {
        super(adress, username, password);
    }
    
    public TimeAccountBean getTimeAccount()
    {
        return null;
    }

}
