package business_logik;

import beans.UserBean;
import database.UserTable;
import java.sql.SQLException;
import java.util.logging.Level;
import java.util.logging.Logger;
import utils.SecurityUtils;

/**
 *
 * @author manuel
 */
public class UserHandling 
{
    private final UserTable m_UserTable;
    private final static UserHandling m_Instance= new UserHandling();
    
    public static UserHandling getInstance()
    {
        return m_Instance;
    }
    
    private UserHandling()
    {
        m_UserTable = new UserTable("wwww.illertech.net/Zeiterfassung", "sa", "odysee2001");
    }
    
    public boolean LoginUser(UserBean bean)
    {
        System.out.println("Login user");
        System.out.println(bean.getUsername());
              
        try
        {
            m_UserTable.getUser(bean.getUsername(), SecurityUtils.makeMD5Checksum(bean.getPassword()));
        }
        catch (SQLException ex)
        {
            Logger.getLogger(UserHandling.class.getName()).log(Level.SEVERE, null, ex);
        }
        
        return true;
    }
    
    public boolean ChangeUserPWD(UserBean bean, String newPwd)
    {
        
        return true;
    }
}
