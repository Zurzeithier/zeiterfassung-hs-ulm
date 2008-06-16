package exceptions;

/**
 *
 * @author manuel
 */
public class DBException extends Exception
{

    public DBException(Throwable cause)
    {
        super(cause);
    }

    public DBException(String message, Throwable cause)
    {
        super(message, cause);
    }

    public DBException(String message)
    {
        super(message);
    }

    public DBException()
    {
    }

}
