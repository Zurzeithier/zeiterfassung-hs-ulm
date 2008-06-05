package controllers;

import beans.UserBean;
import database.ProxyFactory;
import database.UserProxy;
import utils.SecurityUtils;

/**
 *
 * @author manuel
 */
public class UserController
{

    private final UserProxy m_UserProxy;
    private final static UserController m_Instance = new UserController();

    /**
     * 
     * @return
     */
    public static UserController getInstance()
    {
        return m_Instance;
    }

    private UserController()
    {
        m_UserProxy = ProxyFactory.getUserProxy();
    }

    /**
     * 
     * @param username
     * @param password
     * @return
     */
    public boolean LoginUser(String username, String password)
    {
        UserBean dbBean = m_UserProxy.getUser(username);

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
     */
    public boolean ChangeUserPWD(String username, String password, String newPassword)
    {

        UserBean dbBean = m_UserProxy.getUser(username);

        if (dbBean != null && dbBean.getPassword().equals(SecurityUtils.makeMD5Checksum(password)))
        {
            UserBean bean = new UserBean();
            bean.setMid(dbBean.getMid());
            bean.setPassword(SecurityUtils.makeMD5Checksum(newPassword));

            return m_UserProxy.changeUser(bean);
        }

        return false;
    }
}
