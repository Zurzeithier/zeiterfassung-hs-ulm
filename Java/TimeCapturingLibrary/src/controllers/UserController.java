package controllers;

import beans.UserBean;
import database.ProxyFactory;
import database.UserProxy;
import java.sql.SQLException;
import java.util.logging.Level;
import java.util.logging.Logger;
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
        
        try
        {
            dbBean = m_UserProxy.getUser(bean.getUsername());
        }
        catch (SQLException ex)
        {
            Logger.getLogger(UserController.class.getName()).log(Level.SEVERE, null, ex);
            return false;
        }
        
        if (dbBean != null && dbBean.getPassword().equals(SecurityUtils.makeMD5Checksum(bean.getPassword())))
        {
            System.out.println(dbBean.getPassword());
            return true;
        }
        
        return false;
    }
    
    public boolean ChangeUserPWD(UserBean bean, String newPwd)
    {
        
        return true;
    }
}
