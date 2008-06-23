package handlers;

import application.ConfigDataProvider;
import application.data_beans.MailData;
import beans.UserBean;
import database.AdapterPool;
import database.DBAdapter;
import exceptions.DBException;
import pool.ObjectPoolException;
import utils.MailUtils;
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

    /**
     * Changes the attributes of an user
     * @param mid
     * @param bean
     * @return Whether the operation was successful
     * @throws exceptions.DBException
     */
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

    /**
     * Sends a generated password to the user by email
     * @param username
     * @return Whether the operation was successful
     * @throws exceptions.DBException
     */
    public static boolean sendNewPassword(String username) throws DBException
    {
        // test if user is in db
        try
        {
            DBAdapter adapter = AdapterPool.getDBAdapter();
            UserBean bean = null;

            bean = adapter.getUser(username);

             // if user not in db return false
            if (bean == null) 
            {
                return false;
            }

            // generate new pwd and set it to the db
            String newPwd = MailUtils.genertateRandomPassword(8);
            bean.setPassword(SecurityUtils.makeMD5Checksum(newPwd));
            adapter.changeUser(bean);

            // release db adapter
            AdapterPool.releaseDBAdapter(adapter);

            // send mail to user
            String address = "chamaeleon-cms.de";
            String user = "timetracking@chamaeleon-cms.de";
            String password = "geheim";
            ConfigDataProvider.setMailData(new MailData(address, user, password));

            String transmitter = "timetracking@chamaeleon-cms.de";
            String subject = "New Password";
            String content = "Your new password is " + newPwd;

            utils.MailUtils.sendMail(address, user, password, transmitter, username, subject, content);

            return true;
        }
        catch (ObjectPoolException ex)
        {
            throw new DBException(ex);
        }
    }

}
