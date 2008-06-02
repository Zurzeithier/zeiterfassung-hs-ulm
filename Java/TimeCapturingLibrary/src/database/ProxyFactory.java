package database;

/**
 *
 * @author manuel
 */
public class ProxyFactory
{

    public static UserProxy getUserProxy()
    {
        return new MSServerUserProxy(
                        ConnectionDataProvider.getAdress(),
                        ConnectionDataProvider.getUsername(),
                        ConnectionDataProvider.getPassword()
                   );
    }

}
