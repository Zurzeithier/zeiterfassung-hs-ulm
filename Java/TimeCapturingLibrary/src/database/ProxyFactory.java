package database;

import application.ConfigDataProvider;

/**
 *
 * @author manuel
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

}
