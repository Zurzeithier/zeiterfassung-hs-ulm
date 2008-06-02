package database;

import beans.TimePostingBean;

/**
 *
 * @author manuel
 */
public class MSServerTimePostingProxy extends MSServer implements TimePostingProxy
{

    public MSServerTimePostingProxy(String adress, String username, String password)
    {
        super(adress, username, password);
    }

    public TimePostingBean getTimePosting()
    {
        return null;
    }
}
