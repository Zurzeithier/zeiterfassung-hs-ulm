/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package listeners;

import application.ConfigDataProvider;
import application.DBData;
import javax.servlet.ServletContextEvent;
import javax.servlet.ServletContextListener;

/**
 *
 * @author manuel
 */
public class ConfigListener implements ServletContextListener
{
    public void contextInitialized(ServletContextEvent event)
    {

        String adress = event.getServletContext().getInitParameter("config.dbUrl");
        String user = event.getServletContext().getInitParameter("config.dbUser");
        String pwd = event.getServletContext().getInitParameter("config.dbPwd");

        ConfigDataProvider.setDBData(new DBData(adress, user, pwd));
    }

    public void contextDestroyed(ServletContextEvent event)
    {
    }

}
