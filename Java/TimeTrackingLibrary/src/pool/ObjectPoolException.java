/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package pool;

/**
 *
 * @author manuel
 */
public class ObjectPoolException extends Exception
{
    public ObjectPoolException(Throwable cause)
    {
        super(cause);
    }

    public ObjectPoolException(String message, Throwable cause)
    {
        super(message, cause);
    }

    public ObjectPoolException(String message)
    {
        super(message);
    }

    public ObjectPoolException()
    {
    }
}
