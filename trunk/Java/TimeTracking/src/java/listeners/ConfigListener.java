package listeners;

import application.ConfigDataProvider;
import application.data_beans.DBData;
import javax.servlet.ServletContextEvent;
import javax.servlet.ServletContextListener;

/**
 * Listener for the initialisation
 * @author manuel, steffen
 */
public class ConfigListener implements ServletContextListener
{
    /**
     * Initialises the database and the pool size
     * @param event
     */
    public void contextInitialized(ServletContextEvent event)
    {

        String adress = event.getServletContext().getInitParameter("config.dbUrl");
        String user = event.getServletContext().getInitParameter("config.dbUser");
        String pwd = event.getServletContext().getInitParameter("config.dbPwd");

        ConfigDataProvider.setDBData(new DBData(adress, user, pwd));
        
        Integer poolSize = new Integer(event.getServletContext().getInitParameter("config.dbPoolSize"));
        ConfigDataProvider.setDBPoolSize(poolSize);
    }

    /**
     * 
     * @param event
     */
    public void contextDestroyed(ServletContextEvent event)
    {
    }

}
