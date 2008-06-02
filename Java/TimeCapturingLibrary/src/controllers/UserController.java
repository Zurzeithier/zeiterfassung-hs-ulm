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

    public static UserController getInstance()
    {
        return m_Instance;
    }

    private UserController()
    {
        m_UserProxy = ProxyFactory.getUserProxy();
    }

    public boolean LoginUser(UserBean bean)
    {
        UserBean dbBean = null;

        dbBean = m_UserProxy.getUser(bean.getUsername());

        if (dbBean != null && dbBean.getPassword().equals(SecurityUtils.makeMD5Checksum(bean.getPassword())))
        {
            return true;
        }

        return false;
    }

    public boolean ChangeUserPWD(UserBean bean, String newPwd)
    {

        return true;
    }

}
