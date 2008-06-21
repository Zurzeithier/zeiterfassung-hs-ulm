package handlers;

import application.ConfigDataProvider;
import application.data_beans.MailData;
import beans.UserBean;
import database.AdapterPool;
import database.DBAdapter;
import exceptions.DBException;
import pool.ObjectPoolException;
import utils.SecurityUtils;


/**
 * Handler for all user operations
 * @author manuel
 */
public class UserHandler
{

    /**
     * Performs the user login
     * @param username Login name
     * @param password
     * @return Complete user data
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
                returnValue.setPassword(null);  // always delete passwort from bean
            }

            AdapterPool.releaseDBAdapter(adapter);
            
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
            
//---test------------------------------------------------------------------------            
            String address = "chamaeleon-cms.de";   
            String user = "timetracking@chamaeleon-cms.de";
            String password = "geheim";             
            ConfigDataProvider.setMailData(new MailData(address, user, password));
           

            String transmitter = "timetracking@hs-ulm.de";
            String receiver = bean.getUsername();
            String subject = "New Password";
            String content = "Your new password is ...";
            
            utils.MailUtils.sendMail(address, user, password, transmitter, receiver, subject, content);
//---test------------------------------------------------------------------------
            
            AdapterPool.releaseDBAdapter(adapter);
            return returnValue;
        }
        catch (ObjectPoolException ex)
        {
            throw new DBException(ex);
        }
    }

}
