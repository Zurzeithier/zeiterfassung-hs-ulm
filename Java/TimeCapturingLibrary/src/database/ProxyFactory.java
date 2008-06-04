package database;

import application.ConfigDataProvider;

/**
 *
 * @author manuel, steffen
 */
public class ProxyFactory
{

    public static UserProxy getUserProxy()
    {
        return new MSServerUserProxy(
                        ConfigDataProvider.getDBData().adress,
                        ConfigDataProvider.getDBData().user,
                        ConfigDataProvider.getDBData().password);
    }

    public static TimeAccountProxy getTimeAccountProxy()
    {
        return new MSServerTimeAccountProxy(
                        ConfigDataProvider.getDBData().adress,
                        ConfigDataProvider.getDBData().user,
                        ConfigDataProvider.getDBData().password);
    }
        
    public static TimePostingProxy getPostingProxy()
    {
        return new MSServerTimePostingProxy(
                        ConfigDataProvider.getDBData().adress,
                        ConfigDataProvider.getDBData().user,
                        ConfigDataProvider.getDBData().password);
    }

    public static TPTypeProxy getTPTypeProxy()
    {
        return new MSServerTPTypeProxy(
                        ConfigDataProvider.getDBData().adress,
                        ConfigDataProvider.getDBData().user,
                        ConfigDataProvider.getDBData().password);
    }      
}

