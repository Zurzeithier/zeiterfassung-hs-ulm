package controllers;

import beans.UserBean;
import database.AdapterPool;
import exceptions.DBException;
import utils.SecurityUtils;

/**
 *
 * @author manuel
 */
public class UserController
{
    /**
     * 
     * @param username
     * @param password
     * @return
     * @throws DBException 
     */
    public static boolean LoginUser(String username, String password) throws DBException
    {
        UserBean dbBean = AdapterPool.getDBAdapter().getUser(username);

        return (dbBean != null && dbBean.getPassword().equals(SecurityUtils.makeMD5Checksum(password)));
    }

    /**
     * Ändert das Passwort eines Users falls der übergebene Username
     * und das Passwort stimmen.
     * 
     * @param username Username des zu ändernden Users
     * @param password Altes Passwort das geändert werden soll
     * @param newPassword Neues Passwort
     * @return
     * @throws DBException 
     */
    public static boolean ChangeUserPWD(String username, String password, String newPassword) throws DBException
    {

        UserBean dbBean = AdapterPool.getDBAdapter().getUser(username);

        if (dbBean != null && dbBean.getPassword().equals(SecurityUtils.makeMD5Checksum(password)))
        {
            UserBean bean = new UserBean();
            bean.setMid(dbBean.getMid());
            bean.setPassword(SecurityUtils.makeMD5Checksum(newPassword));

            return AdapterPool.getDBAdapter().changeUser(bean);
        }

        return false;
    }
}
