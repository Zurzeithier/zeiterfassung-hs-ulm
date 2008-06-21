package handlers;

import beans.UserBean;
import database.AdapterPool;
import database.DBAdapter;
import exceptions.DBException;
import exceptions.ObjectPoolException;
import utils.SecurityUtils;

/**
 * Handler for all user operations
 * @author manuel
 */
public class UserHandler
{

    /**
     * 
     * @param username
     * @param password
     * @return
     * @throws DBException 
     */
    public static UserBean loginUser(String username, String password) throws DBException
    {
        try
        {
            DBAdapter adapter = AdapterPool.getDBAdapter();
            UserBean returnValue = null;

            UserBean dbBean = adapter.getUser(username);

            if (dbBean != null && dbBean.getPassword().equals(SecurityUtils.makeMD5Checksum(password)))
            {
                returnValue = dbBean;
            }

            AdapterPool.releaseDBAdapter(adapter);
            
            returnValue.setPassword(null);  // always delete passwort from bean
            return returnValue;
        }
        catch (ObjectPoolException ex)
        {
            throw new DBException(ex);
        }
    }

    public static boolean changeUser(int mid, UserBean bean) throws DBException
    {
        try
        {
            DBAdapter adapter = AdapterPool.getDBAdapter();
            boolean returnValue = false;

            if (bean != null)
            {
                if (bean.getPassword() != null)
                {
                    bean.setPassword(SecurityUtils.makeMD5Checksum(bean.getPassword()));
                }

                returnValue = adapter.changeUser(bean);
            }

            AdapterPool.releaseDBAdapter(adapter);
            return returnValue;
        }
        catch (ObjectPoolException ex)
        {
            throw new DBException(ex);
        }
    }

}
